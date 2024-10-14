<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id = null)
    {
        try {
            if($id == 1){
                return Inertia::render('Guest/Blogs/Blog1');
            }

            return Inertia::render('Guest/Blogs/Blog2');

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los blogs');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
