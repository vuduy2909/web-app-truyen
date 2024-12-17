package com.example.apptruyen2.data.list_chapter

import com.google.gson.annotations.SerializedName

data class ChapterItem(
    val name: String,
    val number: Int,
    @SerializedName("story_slug") val slug: String
)
