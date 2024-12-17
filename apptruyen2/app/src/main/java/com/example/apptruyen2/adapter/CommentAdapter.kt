package com.example.apptruyen2.adapter

import android.annotation.SuppressLint
import android.app.Dialog
import android.content.Context
import android.content.Intent
import android.graphics.*
import android.view.*
import android.widget.Button
import androidx.core.text.HtmlCompat
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.Glide
import com.example.apptruyen2.R
import com.example.apptruyen2.activities.EditCommentActivity
import com.example.apptruyen2.data.comments.Comment
import com.example.apptruyen2.databinding.ItemCommentBinding
import com.example.apptruyen2.helper.SharedPreferencesHelper
import com.example.apptruyen2.helper.Util
import com.example.apptruyen2.view.OnCommentBtnClickListener
import com.google.gson.Gson
import java.time.Instant
import java.time.LocalDateTime
import java.time.ZoneId

class CommentAdapter(
  val context: Context,
  val onCommentBtnClickListener: OnCommentBtnClickListener
) : RecyclerView.Adapter<CommentAdapter.ViewHolder>() {

  private val comments: ArrayList<Comment> = arrayListOf()
  val dialog = Dialog(context)

  // Phương thức để gán dữ liệu mới nhất vào adapter, thay thế cho dữ liệu cũ
  @SuppressLint("NotifyDataSetChanged")
  fun setComments(comments: ArrayList<Comment>) {
    this.comments.clear()
    this.comments.addAll(comments)
    notifyDataSetChanged()
  }

  inner class ViewHolder(private val binding: ItemCommentBinding) :
    RecyclerView.ViewHolder(binding.root) {

    private val gson = Gson()
    private val user = SharedPreferencesHelper(context).getUser()

    @SuppressLint("SetTextI18n")
    fun bind(comment: Comment) {
      Glide.with(context).load(comment.userAvatar).into(binding.avatar)
      binding.tvName.text = comment.userName
      binding.tvContent.text =
        HtmlCompat.fromHtml(comment.content, HtmlCompat.FROM_HTML_MODE_COMPACT)

      val now = LocalDateTime.now()
      val instant = Instant.ofEpochMilli(comment.createdAt.time)
      val createdAt = LocalDateTime.ofInstant(instant, ZoneId.systemDefault())
      binding.createdAt.text = Util.getDurationString(createdAt, now)

      replyCommentHandle(comment)

      binding.tvContent.setOnLongClickListener {
        handleCommentDialog(comment)
        return@setOnLongClickListener true
      }

      binding.root.setPadding(comment.index * Util.dpToPx(context, 30), 0, 0, 0)
      println("/-------CHECK------/\n${comment.index * Util.dpToPx(context, 50)}\n/-------/")
    }

    private fun handleCommentDialog(comment: Comment) {
      dialog.setContentView(R.layout.comment_handle_dialog)

      // Thiết lập kích thước của dialog
      val lp = WindowManager.LayoutParams()
      lp.copyFrom(dialog.window?.attributes)
      lp.width = WindowManager.LayoutParams.MATCH_PARENT
      lp.height = WindowManager.LayoutParams.WRAP_CONTENT
      dialog.window?.attributes = lp

      // Thiết lập vị trí của dialog
      val window = dialog.window
      window?.setGravity(Gravity.BOTTOM)

      val editBtn = dialog.findViewById<Button>(R.id.buttonEdit)
      val deleteBtn = dialog.findViewById<Button>(R.id.buttonDelete)

      if (user === null) return
      if (comment.userId != user.id && user.level == "User") return
      if (comment.userId != user.id) editBtn.visibility = View.GONE

      deleteBtn.setOnClickListener {
        onCommentBtnClickListener.onDeleteBtnClick(comment, dialog)
      }

      editBtn.setOnClickListener {
        val intent = Intent(context, EditCommentActivity::class.java)
        intent.putExtra("comment", gson.toJson(comment))
        context.startActivity(intent)
      }

      dialog.show()
    }

    private fun replyCommentHandle(comment: Comment) {
      val user = SharedPreferencesHelper(context).getUser()
      if (user === null) return
      binding.replyCmt.visibility = View.VISIBLE

      binding.replyCmt.setOnClickListener {
        onCommentBtnClickListener.onReplyBtnClick(comment.userName, comment.id)
      }
    }
  }

  override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
    val binding = ItemCommentBinding.inflate(LayoutInflater.from(parent.context), parent, false)
    return ViewHolder(binding)
  }

  override fun onBindViewHolder(holder: ViewHolder, position: Int) {
    val comment = comments[position]

    holder.bind(comment)

    //lắng nghe item click chọn
    holder.itemView.setOnClickListener {

    }
  }

  override fun getItemCount() = comments.size
}

