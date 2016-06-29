// One character letters
t_table1 = "ABVGDEZIJKLMNOPRSTUFHXCYWabvgdezijklmnoprstufhxcy'#w";
w_table1 = "ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÕÖÛÙàáâãäåçèéêëìíîïğñòóôõõöûüúù";

// Two character letters
t_table2 = "YOJOZHCHSHYUJUYAJAJEyojozhchshyujuyajajeYoYoZhChShYuJuYaJaJe";
w_table2 = "¨¨Æ×ØŞŞßßİ¸¸æ÷øşşÿÿı¨¨Æ×ØŞŞßßİ";


function translit2win(str) 
{
 var len = str.length;
 var new_str="";

 for (i = 0; i < len; i++)
 {
  // Check for 2-character letters
  is2char=false;
  if (i < len-1) {
   for(j = 0; j < w_table2.length; j++)
   {
    if(str.substr(i, 2) == t_table2.substr(j*2,2)) {
     new_str+= w_table2.substr(j, 1);
     i++;
     is2char=true;
     break;
    }
   }
  }

  if(!is2char) {
   // Convert one-character letter
   var c = str.substr(i, 1);
   var pos = t_table1.indexOf(c);
   if (pos < 0)
    new_str+= c;
   else 
    new_str+= w_table1.substr(pos, 1);
  }
 }

 return new_str;
}
   