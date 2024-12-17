package com.example.apptruyen2.view

import android.app.Dialog
import com.example.apptruyen2.data.comments.Comment

interface OnCommentBtnClickListener {
  fun onReplyBtnClick(replyUserName: String, replyId: Int)
  fun onDeleteBtnClick(comment: Comment, dialog: Dialog)
}