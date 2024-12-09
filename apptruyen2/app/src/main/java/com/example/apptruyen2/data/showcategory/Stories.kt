package com.example.apptruyen2.data.showcategory

import com.example.apptruyen2.data.liststory.Story
import com.google.gson.annotations.SerializedName

data class Stories(
    val data: ArrayList<Story>,
    @SerializedName("current_page") val currentPage: Int,
    @SerializedName("last_page") val lastPage: Int
)
