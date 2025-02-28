<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function getPage($slug=null){


        try{ 
            if ($slug == "/" || $slug == null) {
                $pageDetails = Pages::where('title', 'Home page')->with('header','footer')->first();
                if (!$pageDetails) {
                    return response()->json(["message" => "Page doesn't exist"], 404);
                }
                return view('Templates.index',['page' => $pageDetails]);
            } else {
                $currentSlug = collect(explode('/', $slug))->last();
                $pageDetails = Pages::where('slug', $currentSlug)->with('header','footer')->first();
                
                if (!$pageDetails) {
                    return response()->json(["message" => "Page doesn't exist"], 404);
                }
                
                return view("Templates.".str_replace('/', '.', $slug),['page' => $pageDetails]);
            }
        }catch(Exception $e){
            \Log::error();
            return response()->json(["message"=>"Error occured while loading" . $e.message()],500);
        }
    }
}
