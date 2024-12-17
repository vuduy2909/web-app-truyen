package com.example.apptruyen2.data.showchapter

import com.google.gson.annotations.SerializedName

data class LinkItem(
    val name: String,
    val number: Int,
    @SerializedName("story_slug") val storySlug: String
)
