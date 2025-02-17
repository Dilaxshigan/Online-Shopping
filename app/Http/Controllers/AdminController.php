<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }
    public  function add_brand()
    {
        return view('admin.add_brands');
    }
    public function store_brand(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $brand =new Brand;

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extension;
        $this->GenerateBrandThumbnailsImage($image,$file_name);
        $brand->image = $file_name;

        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Brand has been added successfully!');
    }
    public function edit_brand($id)
    {
        $brand = Brand::find($id);
        return view('admin.edit_brands',compact('brand'));
    }
    public function update_brand(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $brand = Brand::find($request->id);

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if($request->hasFile('image')){
            if(File::exists(public_path('uploads/brands').'/'.$brand->image))
            {
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }
            
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extension;
        $this->GenerateBrandThumbnailsImage($image,$file_name);
        $brand->image = $file_name;

        }

        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Brand has been updated successfully!');
    }
    public function GenerateBrandThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }
    public function delete_brand($id)
    {
        $brand = Brand::find($id);
        if(File::exists(public_path('uploads/brands').'/'.$brand->image))
        {
            File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
        $brand->delete();

        return redirect()->route('admin.brands')->with('status', 'Brand has been deleted successfully!');
    }
    public function categories()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin.categories', compact('categories'));
    }
    public function add_category()
    {
        return view('admin.add_categories');
    }
    public function store_category(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $category =new Category;

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extension;
        $this->GenerateCategoryThumbnailsImage($image,$file_name);
        $category->image = $file_name;

        $category->save();

        return redirect()->route('admin.categories')->with('status', 'Category has been added successfully!');
    }
    public function edit_category($id)
    {
        $category = Category::find($id);
        return view('admin.edit_categories',compact('category'));
    }
    public function update_category(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $category = Category::find($request->id);


        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if($request->hasFile('image')){
            if(File::exists(public_path('uploads/categories').'/'.$category->image))
            {
                File::delete(public_path('uploads/categories').'/'.$category->image);
            }
            
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extension;
        $this->GenerateCategoryThumbnailsImage($image,$file_name);
        $category->image = $file_name;

        }

        $category->save();

        return redirect()->route('admin.categories')->with('status', 'Category has been updated successfully!');
    }
    public function GenerateCategoryThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }
}
