<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Enterprise;
use App\Category;
use App\Product;
use App\Sale;

use Illuminate\Http\Request;

class BankAccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresasCount   = Enterprise::all()->count();
        $categoriasCount = Category::all()->count();
        $productosCount  = Product::all()->count();
        $salesCount      = Sale::all()->count();

        $cuentas_bancarias = BankAccount::all();

        return view('admin.cuentas_bancarias')
            ->with('empresasCount', $empresasCount)
            ->with('productosCount', $productosCount)
            ->with('salesCount', $salesCount)
            ->with('cb', $cuentas_bancarias)
            ->with('categoriasCount', $categoriasCount);
    }



    public function store(Request $req)
    {
        $bank  = $req->input('bank');
        $code  = $req->input('code');
        $account_number = $req->input('account_number');
        $name  = $req->input('name');
        $dni  = $req->input('dni');
        $phone = $req->input('phone');
        $email = $req->input('email');

        $bankAccount = new BankAccount;

        $bankAccount->bank = $bank;
        $bankAccount->code = $code;
        $bankAccount->account_number   = $account_number;
        $bankAccount->dni  = $dni;
        $bankAccount->name_enterprise  = $name;
        $bankAccount->phone_enterprise = $phone;
        $bankAccount->email_enterprise = $email;

        $bankAccount->save();

        return back()->with('success', 'Cuenta bancaria registrada.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bc = BankAccount::find($id);

        return $bc;
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $req
     * @param  \App\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $bank  = $req->input('bank');
        $code  = $req->input('code');
        $account_number = $req->input('account_number');
        $dni   = $req->input('dni');
        $name  = $req->input('name');
        $phone = $req->input('phone');
        $email = $req->input('email');

        $bankAccount = BankAccount::find($id);

        $bankAccount->bank = $bank;
        $bankAccount->code = $code;
        $bankAccount->account_number   = $account_number;
        $bankAccount->dni  = $dni;
        $bankAccount->name_enterprise  = $name;
        $bankAccount->phone_enterprise = $phone;
        $bankAccount->email_enterprise = $email;

        $bankAccount->save();

        return back()->with('success', 'Cuenta bancaria editada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bc = BankAccount::find($id);
        $bc->delete();

        return back()->with('success', 'Cuenta bancaria eliminada.');
    }
}
