package com.example.apptruyen2.data.liststory

import com.google.gson.annotations.SerializedName

data class Story(
    val name: String,
    val image: String,
    val categories: String?,
    val chapter: Chapter,
    val slug: String,
    @SerializedName("view_count") val viewCount: Int?,
    val stars: Stars?,
    @SerializedName("updated_at") val updatedAt: String
)
