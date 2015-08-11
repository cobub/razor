/*
 * Copyright (c) 2006-2011 Rogério Liesenfeld
 * This file is subject to the terms of the MIT license (see LICENSE.txt).
 */
var cellShown;
var lineSegmentIdsShown;

function hidePath(cell)
{
   if (lineSegmentIdsShown) {
      setOutlines('none');
      cellShown.style.outlineWidth = 'thin';
      lineSegmentIdsShown = null;

      var sameCell = cell == cellShown;
      cellShown = null;
      return sameCell;
   }

   return false;
}

function setOutlines(outlineStyle)
{
   for (var i = 0; i < lineSegmentIdsShown.length; i++) {
      var item = document.getElementById(lineSegmentIdsShown[i]);
      if (item) item.style.outline = outlineStyle;
   }
}

function showPath(cell, lineSegmentIdsStr)
{
   if (hidePath(cell)) return;

   lineSegmentIdsShown = lineSegmentIdsStr.split(' ');
   setOutlines('thin dashed #0000FF');
   cell.style.outlineWidth = 'medium';
   cellShown = cell;
}

function showHide(callPoints, listIndex)
{
   var tableCell = callPoints.parentNode;

   if (listIndex >= 0) {
      tableCell = tableCell.parentNode;
   }
   else {
      listIndex = 0;
   }

   var list = tableCell.getElementsByTagName('ol')[listIndex].style;
   list.display = list.display == 'none' ? 'block' : 'none';
}

var allFilesShown = true;
function showHideAllFiles()
{
   allFilesShown = !allFilesShown;
   var newDisplay = allFilesShown ? 'block' : 'none';
   var rows = document.getElementById('packages').rows;

   for (var i = 0; i < rows.length; i++) {
      var filesCell = rows[i].cells[1];
      var table = filesCell.getElementsByTagName('table')[0];
      if (table) table.style.display = newDisplay;
      filesCell.style.display = newDisplay;
   }
}

function showHideFiles(files)
{
   var filesCell = files.parentNode.cells[1];
   var filesTable = filesCell.getElementsByTagName('table')[0];
   var filesShown = filesCell.style.display != 'none' && filesTable.style.display != 'none';

   if (!allFilesShown) {
      var rows = document.getElementById('packages').rows;

      for (var i = 0; i < rows.length; i++) {
         var filesCell = rows[i].cells[1];

         if (filesShown) {
            filesCell.style.display = 'none';
         }
         else {
            var table = filesCell.getElementsByTagName('table')[0];
            if (table && table != filesTable) table.style.display = 'none';
            filesCell.style.display = 'block';
         }
      }
   }

   filesTable.style.display = filesShown ? 'none' : 'block';
}

function showHideLines(cell)
{
   var content = cell.children;
   var expanded = content[0].style;
   var collapsed = content[1].style;
   var showingExpanded = expanded.display == 'block';
   expanded.display = showingExpanded ? 'none' : 'block';
   collapsed.display = showingExpanded ? 'block' : 'none';
}

var metricCol;
function rowOrder(r1, r2)
{
   var c1 = r1.cells[metricCol];
   var c2 = r2.cells[metricCol];

   if (!c1 || !c2) {
      return -1;
   }

   var t1 = c1.title;
   var t2 = c2.title;

   if (t1 && t2) {
      var s1 = t1.split('/')[1];
      var s2 = t2.split('/')[1];
      return s1 - s2;
   }

   return t1 ? 1 : -1;
}

function sortRows(tbl, metric)
{
   var startRow = 0;
   var endRow = tbl.rows.length;

   if (tbl.id == 'packages') {
      metricCol = 1 + metric;
      startRow = 1;
      endRow--;
   }
   else {
      metricCol = metric;
   }

   var rs = new Array();

   for (var i = startRow; i < endRow; i++) {
      rs[i - startRow] = tbl.rows[i];
   }

   rs.sort(rowOrder);

   for (var i = 0; i < rs.length; i++) {
      rs[i] = rs[i].innerHTML;
   }

   for (var i = 0; i < rs.length; i++) {
      tbl.rows[startRow + i].innerHTML = rs[i];
   }
}

function sortTables(metric)
{
   var tables = document.getElementsByTagName("table");

   for (var i = 0; i < tables.length; i++) {
      sortRows(tables[i], metric);
   }
}