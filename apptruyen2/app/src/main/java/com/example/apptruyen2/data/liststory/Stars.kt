package com.example.apptruyen2.data.liststory

import com.google.gson.annotations.SerializedName

data class Stars(
  val avg: Double,
  @SerializedName("person_count") val personCount: Int
)