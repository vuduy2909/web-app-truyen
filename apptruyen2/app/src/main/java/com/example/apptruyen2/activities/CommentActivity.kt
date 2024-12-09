package com.example.apptruyen2.activities

import android.app.Dialog
import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.view.View
import android.view.inputmethod.InputMethodManager
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.text.HtmlCompat
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.apptruyen2.R
import com.example.apptruyen2.adapter.CommentAdapter
import com.example.apptruyen2.api.RetrofitClient
import com.example.apptruyen2.data.comments.Comment
import com.example.apptruyen2.data.comments.CommentResponse
import com.example.apptruyen2.databinding.ActivityCommentBinding
import com.example.apptruyen2.helper.SharedPreferencesHelper
import com.example.apptruyen2.helper.Util
import com.example.apptruyen2.view.OnCommentBtnClickListener
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

class CommentActivity : AppCompatActivity() {
  private lateinit var binding: ActivityCommentBinding

  private val gson = Gson()
  private lateinit var adapter: CommentAdapter

  private lateinit var recyclerView: RecyclerView
  private lateinit var slug: String
  private lateinit var storyName: String

  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)
    binding = ActivityCommentBinding.inflate(layoutInflater)
    setContentView(binding.root)

    //    kiểm tra xem có bật mạng không nè
    if (!Util.checkNetworkConnected(this)) return

    slug = intent.getStringExtra("slug").toString()
    storyName = intent.getStringExtra("name").toString()
    if (slug === "" || storyName === "") {
      Toast.makeText(this, "Thiếu slug hoặc name", Toast.LENGTH_SHORT).show()
      finish()
      return
    }

    // Hiển thị dữ liệu trong Fragment
    recyclerView = binding.recyclerViewChapter

    //click item trong recyclerView
    adapter = CommentAdapter(this, object: OnCommentBtnClickListener {
      override fun onReplyBtnClick(replyUserName: String, replyId: Int) {
        binding.cancelReplyCmt.visibility = View.VISIBLE
        commentBoxInputHandle(replyUserName, replyId)

        binding.cancelReplyCmt.setOnClickListener {
          binding.cancelReplyCmt.visibility = View.GONE
          binding.commentEditText.setText("")
          commentBoxInputHandle("", null)
        }
      }

      override fun onDeleteBtnClick(comment: Comment, dialog: Dialog) {
        onDeleteCommentHandle(comment, dialog)
      }
    })
    recyclerView.adapter = adapter

    // Mặc định loadListStories() được gọi khi Fragment được khởi tạo
    loadData(slug)

    binding.swipeRefreshLayout.setOnRefreshListener {
      // Thực hiện các thao tác để load lại dữ liệu
      // Sau khi hoàn thành, gọi phương thức setRefreshing(false) để ẩn hiệu ứng làm mới
      loadData(slug)
      binding.swipeRefreshLayout.isRefreshing = false
    }

    commentBoxInputHandle()

    binding.toolbar.setNavigationIcon(R.drawable.baseline_arrow_back_24)
    binding.toolbar.title = "Bình luận truyện $storyName"

    binding.toolbar.setNavigationOnClickListener {
      finish()
    }
  }

  private fun loadData(slug: String) {
    val api = RetrofitClient.create()

    CoroutineScope(Dispatchers.IO).launch {
      val response = api.listCommentsByStory(slug)
      val commentsResponse = gson.fromJson<CommentResponse>(
        response.string(),
        object : TypeToken<CommentResponse>() {}.type
      )

      withContext(Dispatchers.Main) {
        commentsResponse.body?.let {
          val commentTree = flattenCommentTree(it)
          adapter.setComments(commentTree)
//          recyclerView.addItemDecoration(ItemDecoration(this@CommentActivity, commentTree))

          val layoutManager = LinearLayoutManager(this@CommentActivity)
          layoutManager.initialPrefetchItemCount = commentTree.size
          recyclerView.layoutManager = layoutManager
        }
      }
    }
  }

  private fun flattenCommentTree(commentTree: ArrayList<Comment>, count: Int = 0)
    : ArrayList<Comment> {
    val flattenedList: ArrayList<Comment> = arrayListOf()
    var index = count

    for (comment in commentTree) {
      comment.index = count // Gán chỉ số index cho comment hiện tại
      flattenedList.add(comment)
      if (count < 2) index = count + 1;
      if (comment.children.isNotEmpty()) {
        // Đệ quy để phẳng các comment con với chỉ số index tăng dần
        flattenedList.addAll(flattenCommentTree(comment.children, index))
      }
    }

    return flattenedList
  }

  private fun commentBoxInputHandle(replyUserName: String = "", replyId: Int? = null) {
    val user = SharedPreferencesHelper(this).getUser()
    if (user === null) return
    binding.cmtBoxInput.visibility = View.VISIBLE

    if (replyUserName !== "" && replyId !== null) {
      binding.commentEditText.requestFocus()
      binding.commentEditText.setText(
        HtmlCompat.fromHtml("<b>$replyUserName</b>&nbsp;&nbsp;", HtmlCompat.FROM_HTML_MODE_LEGACY)
      )
      binding.commentEditText.setSelection(binding.commentEditText.text.lastIndex)
      // Hiển thị bàn phím để người dùng có thể nhập nội dung
      val imm = getSystemService(Context.INPUT_METHOD_SERVICE) as InputMethodManager
      imm.showSoftInput(binding.commentEditText, InputMethodManager.SHOW_IMPLICIT)
    }

    binding.submitCmtButton.setOnClickListener {
      submitCmt(replyId)
    }
  }

  private fun submitCmt(parentId: Int?) {
    val user = SharedPreferencesHelper(this).getUser()
    if (user === null) return
    val content =
      HtmlCompat.toHtml(binding.commentEditText.text, HtmlCompat.TO_HTML_PARAGRAPH_LINES_INDIVIDUAL)

    if (content == "") {
      Toast.makeText(
        this@CommentActivity,
        "Bạn phải nhập bình luận để có thể gửi",
        Toast.LENGTH_SHORT
      ).show()
      return
    }

    val api = RetrofitClient.create(user.token)

    CoroutineScope(Dispatchers.IO).launch {
      val response = api.createComment(slug, content, parentId)
      val commentsResponse = gson.fromJson<CommentResponse>(
        response.string(),
        object : TypeToken<CommentResponse>() {}.type
      )

      withContext(Dispatchers.Main) {
        if (!commentsResponse.status) {
          Toast.makeText(
            this@CommentActivity,
            "Có lỗi xảy ra! ${commentsResponse.message}",
            Toast.LENGTH_SHORT
          ).show()
          return@withContext
        }

        val intent = Intent(this@CommentActivity, CommentActivity::class.java)
        intent.putExtra("slug", slug)
        intent.putExtra("name", storyName)
        startActivity(intent)
        finish()
      }
    }
  }

  private fun onDeleteCommentHandle(comment: Comment, dialog: Dialog) {
    val user = SharedPreferencesHelper(this).getUser()
    if (user === null) return

    val api = RetrofitClient.create(user.token)

    CoroutineScope(Dispatchers.IO).launch {
      try {
        val response = api.deleteComment(comment.id)
        val commentsResponse = gson.fromJson<CommentResponse>(
          response.string(),
          object : TypeToken<CommentResponse>() {}.type
        )

        withContext(Dispatchers.Main) {
          if (!commentsResponse.status) {
            Toast.makeText(
              this@CommentActivity,
              "Có lỗi xảy ra! ${commentsResponse.message}",
              Toast.LENGTH_SHORT
            ).show()
            return@withContext
          }

          Toast.makeText(
            this@CommentActivity,
            "Xóa comment thành công",
            Toast.LENGTH_SHORT
          ).show()
          loadData(slug)
          dialog.dismiss()
        }
      } catch (e: Throwable) {
        println("---------------- lỗi ---------------------")
        println(e.message)
        println("---------------- lỗi ---------------------")
      }
    }
  }

  override fun onResume() {
    super.onResume()

    adapter.dialog.dismiss()
    loadData(slug)
  }
}