package com.example.apptruyen2.data.listcategory

data class CategoriesResponse(
    val status: Boolean,
    val message: String,
    val body: ArrayList<CategoryItem>
)
