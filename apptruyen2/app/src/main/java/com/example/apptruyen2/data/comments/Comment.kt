package com.example.apptruyen2.data.comments

import com.google.gson.annotations.SerializedName
import java.util.*

data class Comment(
  val id: Int,
  val content: String,
  val parent: Int?,
  @SerializedName("created_at") val createdAt: Date,
  @SerializedName("user_id") val userId: Int,
  @SerializedName("user_name") val userName: String,
  @SerializedName("user_avatar") val userAvatar: String,
  val children: ArrayList<Comment>,
  var index: Int = 0,
)
