<?php

namespace App\Http\Controllers;

use App\Models\DetailBank;
use App\Http\Requests\StoreDetailBankRequest;
use App\Http\Requests\UpdateDetailBankRequest;

class DetailBankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDetailBankRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDetailBankRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetailBank  $detailBank
     * @return \Illuminate\Http\Response
     */
    public function show(DetailBank $detailBank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DetailBank  $detailBank
     * @return \Illuminate\Http\Response
     */
    public function edit(DetailBank $detailBank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDetailBankRequest  $request
     * @param  \App\Models\DetailBank  $detailBank
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDetailBankRequest $request, DetailBank $detailBank)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetailBank  $detailBank
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetailBank $detailBank)
    {
        //
    }
}
