<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDFcategory extends Model {
    protected $fillable = ['title'];
    protected $table = "pdfcategories";
    
    public function pdfs()
    {
        return $this->hasMany(PDF::class, 'category_id');
    }
}
