<?php

namespace App\Http\Controllers\Admin;

use App\Models\ParentModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ParentRequest;

class ParentController extends Controller
{
    public function index(){
        
        $parent = ParentModel::paginate();
        return view('admin.parent.index',compact('parent'));
    }
    
    
    public function create(){
        
        return view('admin.parent.create' , compact('parent'));
    
    }
    
    
    public function store(ParentRequest $request){
        
        $parent = ParentModel::create($request->all());
        $parent->addMedia($request -> file("image"))->toMediaCollection("images");
        return redirect()->route('admin.parent.index')->with('success','Added');
    }
    
    
    public function edit(ParentModel $parent){

        return view('admin.parent.edit',compact('parent'));
    }
    
    
    public function update(ParentRequest $request ,ParentModel $parent){

        $parent->update($request->all());
        if ($request -> file("image")) {
            $parent -> clearMediaCollection("images");
            $parent->addMedia($request -> file("image"))->toMediaCollection("images");
        }
        return redirect()->route('admin.parent.index')->with('success','Updated');
    }
    
    
    public function destroy(ParentModel $parent){

        $parent->delete();
        return back()->with('success','Delete');

    }
}
