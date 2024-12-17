package com.example.apptruyen2.data.showchapter

import com.google.gson.annotations.SerializedName

data class ContentChapter(
    @SerializedName("story_name") val storyName: String,
    @SerializedName("name") val chapterName: String,
    val number: Int,
    val content: String,
    @SerializedName("updated_at") val updatedAt: String
)
