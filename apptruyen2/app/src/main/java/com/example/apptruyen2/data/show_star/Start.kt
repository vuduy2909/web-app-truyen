package com.example.apptruyen2.data.show_star

import com.google.gson.annotations.SerializedName

data class Start(
  @SerializedName("stars_avg") val starsAvg: Double,
  @SerializedName("person_count") val personCount: Int
)
