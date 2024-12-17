package com.example.apptruyen2.model

import android.annotation.SuppressLint
import android.content.ContentValues
import android.content.Context
import android.database.Cursor
import android.database.sqlite.SQLiteDatabase
import com.example.apptruyen2.database.DatabaseHelper

class Chapter(
  var id: Long?,
  var number: Int?,
  var name: String?,
  var content: String?,
  var storyId: Long?
) {
  private lateinit var dbHelper: DatabaseHelper

  fun insert(context: Context): Long {
    dbHelper = DatabaseHelper(context)
    val db: SQLiteDatabase = dbHelper.writableDatabase
    val values = ContentValues()
    values.put("number", number)
    values.put("name", name)
    values.put("content", content)
    values.put("story_id", storyId)
    return db.insert("chapters", null, values)
  }

  fun update(context: Context) {
    dbHelper = DatabaseHelper(context)
    val db: SQLiteDatabase = dbHelper.writableDatabase
    val values = ContentValues()
    values.put("number", number)
    values.put("name", name)
    values.put("content", content)
    values.put("story_id", storyId)
    db.update("chapters", values, "id = ?", arrayOf(id.toString()))
    db.close()
  }

  fun delete(context: Context): Int {
    dbHelper = DatabaseHelper(context)
    val db: SQLiteDatabase = dbHelper.writableDatabase
    dbHelper = DatabaseHelper(context)
    return db.delete("chapters", "id = ?", arrayOf(id.toString()))
  }

  companion object {
    private lateinit var dbHelper: DatabaseHelper

    @SuppressLint("Recycle", "Range")
    fun all(context: Context, storyId: Long, sort: String = "asc"): ArrayList<Chapter> {
      dbHelper = DatabaseHelper(context)
      val db = dbHelper.readableDatabase

      val cursor: Cursor =
        db.rawQuery(
          "SELECT * FROM chapters WHERE story_id = $storyId ORDER BY number $sort",
          null
        )

      val chapters: ArrayList<Chapter> = ArrayList()

      cursor.moveToFirst()
      do {
        // adding the data from cursor to our array list.
        chapters.add(
          Chapter(
            cursor.getLong(0),
            cursor.getInt(1),
            cursor.getString(2),
            cursor.getString(3),
            cursor.getLong(4)
          )
        )
      } while (cursor.moveToNext()) // mov
      cursor.close()
      return chapters
    }

    @SuppressLint("Recycle", "Range")
    fun getChapterByNumber(context: Context, storyId: Long, number: Int): Chapter? {
      dbHelper = DatabaseHelper(context)
      val db = dbHelper.readableDatabase

      val cursor: Cursor =
        db.rawQuery("SELECT * FROM chapters WHERE story_id = $storyId AND number = $number", null)

      if (!cursor.moveToFirst()) return null
      val chapter = Chapter(
        cursor.getLong(0),
        cursor.getInt(1),
        cursor.getString(2),
        cursor.getString(3),
        cursor.getLong(4)
      )

      cursor.close()
      return chapter
    }
  }
}