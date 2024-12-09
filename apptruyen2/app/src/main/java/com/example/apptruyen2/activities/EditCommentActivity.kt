package com.example.apptruyen2.activities

import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.text.HtmlCompat
import com.example.apptruyen2.R
import com.example.apptruyen2.api.RetrofitClient
import com.example.apptruyen2.data.comments.Comment
import com.example.apptruyen2.data.comments.CommentResponse
import com.example.apptruyen2.databinding.ActivityEditCommentBinding
import com.example.apptruyen2.helper.SharedPreferencesHelper
import com.example.apptruyen2.helper.Util
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

class EditCommentActivity : AppCompatActivity() {

  private lateinit var binding: ActivityEditCommentBinding
  private lateinit var comment: Comment

  private val gson = Gson()
  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)

//    kiểm tra xem có bật mạng không nè
    if (!Util.checkNetworkConnected(this)) return

    binding = ActivityEditCommentBinding.inflate(layoutInflater)
    setContentView(binding.root)

    val commentJson = intent.getStringExtra("comment")

    if (commentJson == null) {
      Toast.makeText(this, "Thiếu thông tin comment", Toast.LENGTH_SHORT).show()
      return
    }

    comment = gson.fromJson(commentJson, Comment::class.java)
    binding.commentEditText.setText(HtmlCompat.fromHtml(comment.content, HtmlCompat.FROM_HTML_MODE_LEGACY))

    binding.toolbar.setNavigationIcon(R.drawable.baseline_arrow_back_24)

    binding.toolbar.setNavigationOnClickListener {
      finish()
    }

    binding.cancelCmtButton.setOnClickListener {
      finish()
    }

    binding.submitCmtButton.setOnClickListener {
      onSubmitHandle()
    }
  }

  private fun onSubmitHandle() {
    val user = SharedPreferencesHelper(this).getUser()
    if (user === null) return

    val content =
      HtmlCompat.toHtml(binding.commentEditText.text, HtmlCompat.TO_HTML_PARAGRAPH_LINES_INDIVIDUAL)
    if (content == "") {
      Toast.makeText(this, "Bình luận không thể để trống", Toast.LENGTH_SHORT).show()
      return
    }
    val api = RetrofitClient.create(user.token)

    CoroutineScope(Dispatchers.IO).launch {
      try {
        val response = api.updateComment(comment.id, content)
        val commentsResponse = gson.fromJson<CommentResponse>(
          response.string(),
          object : TypeToken<CommentResponse>() {}.type
        )

        withContext(Dispatchers.Main) {
          if (!commentsResponse.status) {
            Toast.makeText(
              this@EditCommentActivity,
              "Có lỗi xảy ra! ${commentsResponse.message}",
              Toast.LENGTH_SHORT
            ).show()
            return@withContext
          }

          Toast.makeText(
            this@EditCommentActivity,
            "Sửa comment thành công",
            Toast.LENGTH_SHORT
          ).show()
          finish()
        }
      } catch (e: Throwable) {
        println("---------------- lỗi ---------------------")
        println(e.message)
        println("---------------- lỗi ---------------------")
      }
    }
  }
}