package com.example.apptruyen2.helper

import android.content.Context
import android.graphics.Canvas
import android.graphics.Paint
import android.graphics.Path
import androidx.core.content.ContextCompat
import androidx.core.view.get
import androidx.recyclerview.widget.RecyclerView
import com.example.apptruyen2.R
import com.example.apptruyen2.data.comments.Comment

class ItemDecoration(context: Context, private val flattenCommentTree: ArrayList<Comment>) : RecyclerView.ItemDecoration()  {
  private val dividerPaint = Paint().apply {
    style = Paint.Style.STROKE
    strokeWidth = 4f
    color = ContextCompat.getColor(context, R.color.orange_1)
  }

  override fun onDraw(c: Canvas, parent: RecyclerView, state: RecyclerView.State) {
    super.onDraw(c, parent, state)

    val childCount = parent.childCount
    for (i in 0 until childCount) {
      val child = parent.getChildAt(i)
      val cmtChild = flattenCommentTree[i]

      if (cmtChild.parent == null) continue

      child.left = cmtChild.index * Util.dpToPx(child.context, 50)

      val cmtParentId = cmtChild.parent

      var cmtParentIndex = flattenCommentTree.indexOfFirst { it.id == cmtParentId }
      if (cmtChild.index == 2 && flattenCommentTree[cmtParentIndex].index == 2) {
        cmtParentIndex =
          flattenCommentTree.indexOfFirst {it.id == flattenCommentTree[cmtParentIndex].parent }
      }

      val parentView = parent[cmtParentIndex]

      val startX = parentView.left.toFloat() + Util.dpToPx(child.context, 20)
      val startY = parentView.top.toFloat() + Util.dpToPx(child.context, 40)
      val endX = child.left.toFloat()
      val endY = child.top.toFloat() + Util.dpToPx(child.context, 20)

      val path = Path()
      path.moveTo(startX, startY)
      path.lineTo(startX, endY)
      path.lineTo(endX, endY)

      c.drawPath(path, dividerPaint)
    }
  }
}