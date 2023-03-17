<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use Harimayco\Menu\Models\Menus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Harimayco\Menu\Models\MenuItems;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function createNewMenu()
    {
        $menu = new Menus();
        $menu->name = request()->input("menuname");
        $menu->save();
        return json_encode(array("resp" => $menu->id));
    }

    public function deleteItemMenu()
    {
        try {
            DB::beginTransaction();
            $menuitem = MenuItems::find(request()->input("id"));
            $menuitem->delete();
            DB::commit();

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'Menu item deleted successfully.'
            ], JsonResponse::HTTP_OK);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong, try again.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteMenu()
    {
        $menus = new MenuItems();
        $getall = $menus->getall(request()->input("id"));
        if (count($getall) == 0) {
            $menudelete = Menus::find(request()->input("id"));
            $menudelete->delete();

            return json_encode(array("resp" => "you delete this item"));
        } else {
            return json_encode(array("resp" => "You have to delete all items first", "error" => 1));
        }
    }

    public function updateItem()
    {
        try {
            DB::beginTransaction();
            $arraydata = request()->input("arraydata");
            if (is_array($arraydata)) {
                foreach ($arraydata as $value) {
                    $menuitem = MenuItems::find($value['id']);
                    $menuitem->label = $value['label'];
                    $menuitem->link = $value['link'];
                    $menuitem->class = $value['class'];
                    if (config('menu.use_roles')) {
                        $menuitem->role_id = $value['role_id'] ? $value['role_id'] : 0;
                    }
                    $menuitem->save();
                }
            } else {
                $menuitem = MenuItems::find(request()->input("id"));
                $menuitem->label = request()->input("label");
                $menuitem->link = request()->input("url");
                $menuitem->class = request()->input("clases");
                if (config('menu.use_roles')) {
                    $menuitem->role_id = request()->input("role_id") ? request()->input("role_id") : 0;
                }
                $menuitem->save();
            }
            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'Menu item updated successfully'
            ], JsonResponse::HTTP_OK);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong, try again.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addCustomMenu()
    {
        try {
            DB::beginTransaction();
            $menuitem = new MenuItems();
            $menuitem->label = request()->input("labelmenu");
            $menuitem->link = request()->input("linkmenu");
            if (config('menu.use_roles')) {
                $menuitem->role_id = request()->input("rolemenu") ? request()->input("rolemenu")  : 0;
            }
            $menuitem->menu = request()->input("idmenu");
            $menuitem->sort = MenuItems::getNextSortRoot(request()->input("idmenu"));
            $menuitem->save();
            DB::commit();

            flash()->success('Menu item added successfully.');
        } catch (Throwable $th) {
            DB::rollBack();
            flash()->error('Something went wrong, try again.');
        }
    }

    public function generateMenuControl()
    {
        try {
            DB::beginTransaction();
            $menu = Menus::find(request()->input("idmenu"));
            $menu->name = request()->input("menuname");

            $menu->save();
            if (is_array(request()->input("arraydata"))) {
                foreach (request()->input("arraydata") as $value) {

                    $menuitem = MenuItems::find($value["id"]);
                    $menuitem->parent = $value["parent"];
                    $menuitem->sort = $value["sort"];
                    $menuitem->depth = $value["depth"];
                    if (config('menu.use_roles')) {
                        $menuitem->role_id = request()->input("role_id");
                    }
                    $menuitem->save();
                }
            }
            echo json_encode(array("resp" => 1));
            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'Menu item updated successfully'
            ], JsonResponse::HTTP_OK);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong, try again.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
