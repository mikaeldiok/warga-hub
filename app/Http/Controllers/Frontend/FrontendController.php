<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Modules\System\Services\GroupService;

class FrontendController extends Controller
{

    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $body_class = '';

        $groupResponse = $this->groupService->getAllGroup();

        if(!$groupResponse->error){
            $groups = $groupResponse->data;
        }else{
            $groups = NULL;
        }

        return view("frontend.index",
            compact('body_class','groups')
        );

    }

    /**
     * Show the about.
     *
     * @return \Illuminate\Http\Response
     */
    public function about()
    {
        $body_class = '';

        return view("frontend.about",
            compact('body_class')
        );

    }

    /**
     * Show the gallery.
     *
     * @return \Illuminate\Http\Response
     */
    public function gallery()
    {
        $body_class = '';

        return view("frontend.gallery",
            compact('body_class')
        );

    }

    /**
     * Privacy Policy Page.
     *
     * @return \Illuminate\Http\Response
     */
    public function privacy()
    {
        $body_class = '';

        return view('frontend.privacy', compact('body_class'));
    }

    /**
     * Terms & Conditions Page.
     *
     * @return \Illuminate\Http\Response
     */
    public function terms()
    {
        $body_class = '';

        return view('frontend.terms', compact('body_class'));
    }
}
