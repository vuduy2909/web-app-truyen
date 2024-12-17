package com.example.apptruyen2.data.liststory

import com.google.gson.annotations.SerializedName

data class Chapter(
    @SerializedName("is_full") val isFull: Boolean,
    val count: Int
)
