<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ConditionNumberCollection;
use App\Models\Condition;
use App\Models\ConditionNumber;

class ConditionNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new ConditionNumberCollection(ConditionNumber::all());
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
