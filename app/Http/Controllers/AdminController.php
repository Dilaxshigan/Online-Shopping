<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
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
     // **************** Brand Section ****************
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
        // **************** Category Section ****************
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
    public function delete_category($id)
    {
        $category = Category::find($id);
        if(File::exists(public_path('uploads/categories').'/'.$category->image))
        {
            File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();

        return redirect()->route('admin.categories')->with('status', 'Category has been deleted successfully!');
    }
     // **************** Product Section ****************
    public function products()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.products',compact('products'));
    }
    public function add_product()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();

        return view('admin.add_product', compact('categories','brands'));
    }
    public function store_product(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:products,slug',
            'category_id'=>'required',
            'brand_id'=>'required',            
            'short_description'=>'required',
            'description'=>'required',
            'regular_price'=>'required',
            'sale_price'=>'required',
            'SKU'=>'required',
            'stock_status'=>'required',
            'featured'=>'required',
            'quantity'=>'required',
            'image'=>'required|mimes:png,jpg,jpeg,webp|max:2048'            
        ]);

        $product = new Product;

        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;

        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $imageName = $current_timestamp.'.'.$image->extension();
            $this->GenerateProductThumbnailsImage($image,$imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;
    
        if($request->hasFile('images'))
        {
            $allowedfileExtension=['jpg','png','jpeg','webp'];
            $files = $request->file('images');
            foreach($files as $file){                
                $gextension = $file->getClientOriginalExtension();                                
                $check=in_array($gextension,$allowedfileExtension);            
                if($check)
                {
                    $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;   
                    $this->GenerateProductThumbnailsImage($file,$gfilename);                    
                    array_push($gallery_arr,$gfilename);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }

        $product->images = $gallery_images;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $product->save();
        return redirect()->route('admin.products')->with('status','Product has been added successfully !');
    }
    public function GenerateProductThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/products');
        $destinationPathThumbnail = public_path('uploads/products/thumbnails');
        $img = Image::read($image->path());
        $img->cover(540,689,"top");

        $img->resize(540,689,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);

        $img->resize(104,104,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail.'/'.$imageName);
    }
    public function edit_product($id)
    {
        $product = Product::find($id);

        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();

        return view('admin.edit_products', compact('product','categories','brands'));
    }
    public function update_product(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,' . $request->id, // Fixed validation
            'category_id' => 'required',
            'brand_id' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048' // Made nullable
        ]);
    
        $product = Product::find($request->id);
    
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
    
        $current_timestamp = Carbon::now()->timestamp;
    
        if($request->hasFile('image'))
        {
            if(File::exists(public_path('uploads/products') . '/' . $product->image))
            {
                File::delete(public_path('uploads/products') . '/' . $product->image);
            }
            if(File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image))
            {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
            }
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailsImage($image, $imageName);
            $product->image = $imageName;
        }
    
        $gallery_arr = [];
        $gallery_images = "";
        $counter = 1;
    
        if($request->hasFile('images'))
        {
            foreach (explode(',', $product->images) as $ofile)
            {
                if(File::exists(public_path('uploads/products') . '/' . $ofile))
                {
                    File::delete(public_path('uploads/products') . '/' . $ofile);
                }
                if(File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile))
                {
                    File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
                }
            }
    
            $allowedfileExtension = ['jpg', 'png', 'jpeg', 'webp'];
            $files = $request->file('images');
            foreach($files as $file){                
                $gextension = $file->getClientOriginalExtension();                                
                if(in_array($gextension, $allowedfileExtension))
                {
                    $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;   
                    $this->GenerateProductThumbnailsImage($file, $gfilename);                    
                    array_push($gallery_arr, $gfilename);
                    $counter++;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
            $product->images = $gallery_images;
        }
    
        $product->save();
        
        return redirect()->route('admin.products')->with('status', 'Product has been updated successfully!');
    }
    public function delete_product($id)
    {
        $product = Product::find($id);
        if(File::exists(public_path('uploads/products') . '/' . $product->image))
        {
            File::delete(public_path('uploads/products') . '/' . $product->image);
        }
        if(File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image))
        {
            File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
        }
        foreach (explode(',', $product->images) as $ofile)
        {
            if(File::exists(public_path('uploads/products') . '/' . $ofile))
            {
                File::delete(public_path('uploads/products') . '/' . $ofile);
            }
            if(File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile))
            {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
            }
        }
        $product->delete();

        return redirect()->route('admin.products')->with('status', 'Product has been deleted successfully!');
    }
    public function coupons()
    {
        $coupons = Coupon::orderBy("expiry_date","DESC")->paginate(12);
        return view("admin.coupons",compact("coupons"));
    }
        
}
