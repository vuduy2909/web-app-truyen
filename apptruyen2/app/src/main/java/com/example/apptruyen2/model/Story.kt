package com.example.apptruyen2.model

import android.annotation.SuppressLint
import android.content.ContentValues
import android.content.Context
import android.database.Cursor
import android.database.sqlite.SQLiteDatabase
import com.example.apptruyen2.database.DatabaseHelper
class Story(
  var id: Long?,
  var name: String?,
  var image: String?,
  var slug: String?,
) {
  private lateinit var dbHelper: DatabaseHelper

  fun insert(context: Context): Long {
    dbHelper = DatabaseHelper(context)
    val db: SQLiteDatabase = dbHelper.writableDatabase
    dbHelper = DatabaseHelper(context)
    val values = ContentValues()
    values.put("name", name)
    values.put("image", image)
    values.put("slug", slug)
    return db.insert("stories", null, values)
  }

  fun delete(context: Context): Int {
    dbHelper = DatabaseHelper(context)
    val db: SQLiteDatabase = dbHelper.writableDatabase
    dbHelper = DatabaseHelper(context)
    return db.delete("stories", "id = ?", arrayOf(id.toString()))
  }

  companion object {
    private lateinit var dbHelper: DatabaseHelper

    @SuppressLint("Recycle", "Range")
    fun all(context: Context): ArrayList<Story> {
      dbHelper = DatabaseHelper(context)
      val db = dbHelper.readableDatabase

      val cursor: Cursor = db.rawQuery("SELECT * FROM stories", null)

      val stories: ArrayList<Story> = ArrayList()

      if (!cursor.moveToFirst()) return stories
      do {
        // adding the data from cursor to our array list.

        stories.add(
          Story(
            cursor.getLong(0),
            cursor.getString(1),
            cursor.getString(2),
            cursor.getString(3)
          )
        )
      } while (cursor.moveToNext()) // mov
      cursor.close()
      return stories
    }

    fun getStoryBySlug(context: Context, slug: String?): Story? {
      dbHelper = DatabaseHelper(context)
      val db = dbHelper.readableDatabase

      val query = "SELECT * FROM stories WHERE slug = ?"
      val cursor = db.rawQuery(query, arrayOf(slug))

      if (!cursor.moveToFirst()) return null

      val story =  Story(
        cursor.getLong(0),
        cursor.getString(1),
        cursor.getString(2),
        cursor.getString(3)
      )
      cursor.close()
      return story
    }
  }
}