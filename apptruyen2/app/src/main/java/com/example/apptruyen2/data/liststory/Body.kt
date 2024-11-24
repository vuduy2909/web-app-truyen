package com.example.apptruyen2.data.liststory

import com.google.gson.annotations.SerializedName

data class Body(
    @SerializedName("current_page") val currentPage: Int,
    val data: ArrayList<Story>,
    @SerializedName("last_page") val lastPage: Int,
)
