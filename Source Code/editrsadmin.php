#!/usr/bin/php
<!-- /////////////////////////////////////// Form to edit research administration///////////////////////////////////////// -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- /////////////////////////////////////// Css Declaration //////////////////////////////////////////////////////////// -->
<link href="CSS/phd.css" rel="stylesheet" type="text/css" />
<link href="CSS/header.css" rel="stylesheet" type="text/css" />
<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<!-- //// Java Script to make auto sugesstion function //// -->
<script type="text/javascript">
<!-- Declare the array and store the values according to your usage -->
var suggestions = new Array("Distributed systems","Vision","Theory", "AI", "High Performance Computing", "Bioinformatics", "Hardware", "Language Design", "Operating Systems", "Performance Analysis", "Finance", "Databases", "Logic", "Security", "Software Engineering", "Multimedia", "Information Retrievel", "Datamining", "Communicating Agents", "distributed systems","vision","theory", "aI", "high Performance Computing", "bioinformatics", "hardware", "language Design", "operating Systems", "performance Analysis", "finance", "databases", "logic", "security", "software Engineering", "multimedia", "information Retrievel", "datamining", "communicating Agents");
var outp;
var oldins;
var posi = -1;
var words = new Array();
var input;
var key;
function setVisible(visi)
{
  var x = document.getElementById("shadow");
  var t = document.getElementsByName("research_subject")[0];
  x.style.position = 'absolute';
  x.style.top = (findPosY(t)+3)+"px";
  x.style.left = (findPosX(t)+2)+"px";
  x.style.visibility = visi;
}
function init()
{
  outp = document.getElementById("output");
  window.setInterval("lookAt()", 100);
  setVisible("hidden");
  document.onkeydown = keygetter; //needed for Opera...
  document.onkeyup = keyHandler;
}
function findPosX(obj)
{
  var curleft = 0;
  if (obj.offsetParent)
  {
    while (obj.offsetParent)
    {
      curleft += obj.offsetLeft;
      obj = obj.offsetParent;
    }
   }
  else if (obj.x)
    curleft += obj.x;
        return curleft;
}
function findPosY(obj)
{
  var curtop = 0;
  if (obj.offsetParent)
  {
    curtop += obj.offsetHeight;
    while (obj.offsetParent)
    {
      curtop += obj.offsetTop;
      obj = obj.offsetParent;
     }
   }
   else if (obj.y)
   {
     curtop += obj.y;
     curtop += obj.height;
   }
   return curtop;
}
function lookAt()
{
   var ins = document.getElementsByName("research_subject")[0].value;
   if (oldins == ins)
      return;
   else if (posi > -1);
   else if (ins.length > 0)
   {
     words = getWord(ins);
     if (words.length > 0)
     {
        clearOutput();
        for (var i=0;i < words.length; ++i)
             addWord (words[i]);
        setVisible("visible");
        input = document.getElementsByName("research_subject")[0].value;
     }
     else
     {
        setVisible("hidden");
        posi = -1;
     }
   }
   else
   {
    setVisible("hidden");
    posi = -1;
   }
   oldins = ins;
}
function addWord(word)
{
  var sp = document.createElement("div");
  sp.appendChild(document.createTextNode(word));
  sp.onmouseover = mouseHandler;
  sp.onmouseout = mouseHandlerOut;
  sp.onclick = mouseClick;
  outp.appendChild(sp);
}
function clearOutput()
{
  while (outp.hasChildNodes())
  {
    noten=outp.firstChild;
    outp.removeChild(noten);
  }
   posi = -1;
}
function getWord(beginning)
{
  var words = new Array();
  for (var i=0;i < suggestions.length; ++i)
   {
    var j = -1;
    var correct = 1;
    while (correct == 1 && ++j < beginning.length)
    {
     if (suggestions[i].charAt(j) != beginning.charAt(j))
         correct = 0;
    }
    if (correct == 1)
       words[words.length] = suggestions[i];
  }
    return words;
  
}       
function setColor (_posi, _color, _forg)
{
   outp.childNodes[_posi].style.background = _color;
   outp.childNodes[_posi].style.color = _forg;
}
function keygetter(event)
{
  if (!event && window.event) 
      event = window.event;
  if (event)
      key = event.keyCode;
  else
      key = event.which;
}
function keyHandler(event)
{
  if (document.getElementById("shadow").style.visibility == "visible")
  {
     var textfield = document.getElementsByName("research_subject")[0];
     if (key == 40)//key down
     { 
        if (words.length > 0 && posi <= words.length-1)
        {
          if (posi >=0)
            setColor(posi, "#fff", "black");
          else 
             input = textfield.value;
             setColor(++posi, "blue", "white");
             textfield.value = outp.childNodes[posi].firstChild.nodeValue;
        }
      }
      else if (key == 38)
      { //Key up
        if (words.length > 0 && posi >= 0)
         {
           if (posi >=1)
           {
              setColor(posi, "#fff", "black");
              setColor(--posi, "blue", "white");
              textfield.value = outp.childNodes[posi].firstChild.nodeValue;
           }
           else
           {
              setColor(posi, "#fff", "black");
              textfield.value = input;
              textfield.focus();
              posi--;
           }
         }
        }
         else if (key == 27)
         { // Esc
            textfield.value = input;
            setVisible("hidden");
            posi = -1;
            oldins = input;
          }
          else if (key == 8) 
          { // Backspace
            posi = -1;
            oldins=-1;
          } 
              }
   }
    var mouseHandler=function()
    {
      for (var i=0; i < words.length; ++i)
        setColor (i, "white", "black");
      this.style.background = "blue";
      this.style.color= "white";
     }
     var mouseHandlerOut=function()
     {
       this.style.background = "white";
       this.style.color= "black";
     }
     var mouseClick=function()
     {
        document.getElementsByName("research_subject")[0].value = this.firstChild.nodeValue;
        setVisible("hidden");
        posi = -1;
        oldins = this.firstChild.nodeValue;
     }
</script>



<!--///////////////////////////////////////// Check access permission /////////////////////////////////////////////////// -->
<?php 
	include "func/access.php";
	$admin=check_admin();
	if (!$admin){
		echo "Unauthorised access";
		return;
	}
?>
<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<?php
	$user=$_SERVER["REMOTE_USER"];
	$_admission_id = $_GET['id'];
	$_query= $_GET['query'];
	$_type= $_GET['type'];
	$_surname=$_GET['su'];
	$_forename=$_GET['fo'];
?>


<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Edit Research Admin</title>
        <P class ="centre_blue">Edit Research Admin</p><HR><BR></br></hr>
    </head>
    <body class = "LightGoldenRodYellow" onLoad="init();" >

        <?php
			// Get the existed info in case of editing to avoid re type data
            if (!$link){$link=connectdb();}
            $query_adm_info= "SELECT research_subject, admin_tutor_comment 
								FROM phd_admissions WHERE admission_id=$_admission_id ";
            $result= pg_query($query_adm_info);
            $rows= pg_num_rows($result);
            if($rows>0){
                $arr=pg_fetch_array($result,0);
                $_research_subject=$arr[0];
                $_tutor_comment=$arr[1];
            } 
			
        ?>
		<!--  The actual form body -->
        <form <?php echo "action='updatersadmin.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename'" ?> method="post" >
            <table border="0">
                <tr><td><label><B>Research Subject</b> </label></td>
                <td><input name="research_subject" id="research_subject" type="text" <?php echo "value='$_research_subject'";?> autocomplete="off">
                </td></tr>
                <tr><td>&nbsp; </td></tr>
                <tr><td><label><B>Admin Tutor's Comment</b></label></td>
                <td><textarea name= "comment" cols="60" rows="4" enctype="multiport/form-data"><?php echo $_tutor_comment; ?></textarea></td></tr>
            </table>
            <input type="submit" value="Update" />
        </form>
        <div class="shadow" id="shadow">
			<div class="output" id="output"></div>
		</div>
   
            <HR><div align="right">
            	<?php
                    if($admin){
                        echo "<a href='./main.php'>PhD Admission Portal</a>>";
                    }
                ?>
                <?php echo "<a href='./sortapp.php?type=$_type&query=$_query'>$_query Applicants</a>";?>
                ><?php echo "<a href='viewapplication.php?id=$_admission_id&query=$_query&type=$_type'> $_forename ($_surname)</a>";?>>
                <?php echo "<a href='spvsinfo.php?id=$_admission_id&query=$_query&type=$_type&su=$_surname&fo=$_forename'> Research Info </a>";?>>
                <b><i>Edit</i></b>
            </div><BR>
    </body>
</html>


