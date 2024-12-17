package com.example.apptruyen2.data.history

import com.google.gson.annotations.SerializedName

data class History(
  val name: String,
  val image: String,
  val slug: String,
  @SerializedName("chapter_number") val chapterNumber: Int,
)
