<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubdomainStoreRequest;
use App\Models\Subdomain;
use Illuminate\Http\Request;

class SubdomainController extends Controller
{
    public function index($name)
    {
        $subdomain = Subdomain::where('name', $name)->first();
        if (!$subdomain || $subdomain->isExpired() || !$subdomain->toml) {
            abort(404);
        }
        $toml = json_decode($subdomain->toml);
        $output = "";
        $out_variable = function($k, $v) use (&$output){
            if(is_string($v)){
                $output.= $k. " = \"". $v . "\"\n";                
            }
            else{
                $output.= $k. " = ". $v . "\n";                
            }
        };
        foreach ($toml as $key => $value) {
            if($key=="CURRENCIES") {
                foreach ($value as $currency){
                    $output .= "\n[[CURRENCIES]]\n";
                    foreach ($currency as $k => $v){
                        $output .= $out_variable($k, $v);                        
                    }
                } 
                $output .= "\n";
            }
            else{
                $out_variable($key, $value);
            }
        }
        return view('toml', ['data'=>$output]);
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
    public function store(SubdomainStoreRequest $request)
    {
        if($request->user()->subdomain){
            return response()->json([
                'status'=>false,
                'message'=>'User already has a subdomain'
            ], 400);
        }
        $toml_data = $request->all();
        $subdomain_data = ['name'=>$toml_data['name']];
        $subdomain_data['user_id'] = $request->user()->id;
        $subdomain_data['expiration_date'] = fake()->dateTimeBetween('+6 months', '+1 years');
        unset($toml_data['name']);
        $subdomain_data['toml'] = json_encode($toml_data);
        $subdomain = Subdomain::create($subdomain_data);
        if(!$subdomain){
            return response()->json([
                'status'=>false,
                'message'=>'Invalid subdomain data'
            ], 400);
        }
        return response()->json([
           'status'=>true,
           'data'=>$toml_data
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return response()->json(['status'=>true, 'data'=>$request->user()->subdomain->getData()]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubdomainStoreRequest $request)
    {
        
        $toml_data = $request->all();
        $subdomain = $request->user()->subdomain;
        if(!$subdomain){
            return response()->json([
                'status'=>false,
                'message'=>'User has no subdomain'
            ], 400);
        }

        $subdomain->name = $toml_data['name'];
        unset($toml_data['name']);
        $subdomain->toml = json_encode($toml_data);
        $subdomain->save();
        return response()->json([
           'status'=>true,
           'data'=>$subdomain->getData()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $subdomain = $request->user()->subdomain;
        if(!$subdomain){
            return response()->json([
                'status'=>false
            ], 400);
        }
        $subdomain->delete();

        
        return response()->json([
            'status'=>true,
         ], 200);
    }
}
