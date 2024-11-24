package com.example.apptruyen2.data.showstory

import com.example.apptruyen2.data.liststory.Chapter
import com.google.gson.annotations.SerializedName

data class InfoStory(
    val name: String,
    val chapter: Chapter,
    @SerializedName("view_count") val viewCount: Int?,
    val image: String,
    val descriptions: String,
    val slug: String,
    val author: Author,
    @SerializedName("categories") val categoriesStory: ArrayList<CategoriesStory>
)
