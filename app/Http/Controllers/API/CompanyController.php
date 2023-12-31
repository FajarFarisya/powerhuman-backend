<?php

namespace App\Http\Controllers\API;

use App\Models\Company;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Requests\CreateCompanyRequest;
use Exception;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function all(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        // powerhuman.com/api/company?id=1
        if($id){
            $company = Company::with(['users'])->find($id);

            if($company){
                return ResponseFormatter::success($company, 'Company Found');
            }

            return ResponseFormatter::error('Company Not Found', 404);
        }

        // powerhuman.com/api/company
        $companies = Company::with(['users']);

        // powerhuman.com/api/company?name=Kunde
        if($name){
            $companies->where('name', 'like', '%'.$name.'%');
        }

        return ResponseFormatter::success($companies->paginate($limit), 'Companies Found');
    }

    public function create(CreateCompanyRequest $request){
        try {
            //Upload logo
            if($request->hasFile('logo')){
                $path = $request->file('logo')->store('public/logos');
            }

            //Create Company
            $company = Company::create([
                'name' => $request->name,
                'logo' => $path,
            ]);


            if(!$company){
                throw new Exception("Company not created!");
            }

            //Attach company to user
            $user = User::find(Auth::id());
            $user->companies()->attach($company->id);

            //load user at company
            $company->load('users');

            return ResponseFormatter::success($company, 'Company Created!');

        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }

    }
}
