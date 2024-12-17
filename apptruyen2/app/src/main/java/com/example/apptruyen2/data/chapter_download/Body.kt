package com.example.apptruyen2.data.chapter_download

import com.google.gson.annotations.SerializedName

data class Body(
    @SerializedName("current_page") val currentPage: Int,
    val data: ArrayList<Chapter>,
    @SerializedName("last_page") val lastPage: Int,
    val from: Int,
    val to: Int
)
