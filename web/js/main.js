/******************************************/
/* FONCTIONS ******************************/
/******************************************/
function preview(textareaId, previewDiv) {
   if(typeof previewDiv == 'undefined') {var field = textareaId;}
   else{var field = textareaId.value;} // Le contenu du textarea est dans la variable field
   // Si le top prévisualisation est vrai et que du contenu à été rédigé.
   if (document.querySelector('#previsualisation').checked && field) {
      
      var smiliesName = new Array(':magicien:', ':colere:', ':diable:', ':ange:', ':ninja:', '&gt;_&lt;', ':pirate:', ':zorro:', ':honte:', ':soleil:', ':\'\\(', ':waw:', ':\\)', ':D', ';\\)', ':p', ':lol:', ':euh:', ':\\(', ':o', ':colere2:', 'o_O', '\\^\\^', ':\\-°');
      var smiliesUrl  = new Array('magicien.png', 'angry.gif', 'diable.png', 'ange.png', 'ninja.png', 'pinch.png', 'pirate.png', 'zorro.png', 'rouge.png', 'soleil.png', 'pleure.png', 'waw.png', 'smile.png', 'heureux.png', 'clin.png', 'langue.png', 'rire.gif', 'unsure.gif', 'triste.png', 'huh.png', 'mechant.png', 'blink.gif', 'hihi.png', 'siffle.png');
      var smiliesPath = "http://www.siteduzero.com/Templates/images/smilies/";
   
      field = field.replace(/&/g, '&amp;');
      field = field.replace(/</g, '&lt;').replace(/>/g, '&gt;');
      field = field.replace(/\n/g, '<br />').replace(/\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
      
      field = field.replace(/&lt;gras&gt;([\s\S]*?)&lt;\/gras&gt;/g, '<strong>$1</strong>');
      field = field.replace(/&lt;italique&gt;([\s\S]*?)&lt;\/italique&gt;/g, '<em>$1</em>');
      field = field.replace(/&lt;souligne&gt;([\s\S]*?)&lt;\/souligne&gt;/g, '<ins>$1</ins>');
      field = field.replace(/&lt;cite&gt;([\s\S]*?)&lt;\/cite&gt;/g, '<cite>$1</cite>');
      field = field.replace(/&lt;lien&gt;([\s\S]*?)&lt;\/lien&gt;/g, '<a href="$1">$1</a>');
      field = field.replace(/&lt;lien url="([\s\S]*?)"&gt;([\s\S]*?)&lt;\/lien&gt;/g, '<a href="$1" title="$2">$2</a>');
      field = field.replace(/&lt;image&gt;([\s\S]*?)&lt;\/image&gt;/g, '<img src="$1" alt="Image" />');
      field = field.replace(/&lt;citation nom=\"(.*?)\"&gt;([\s\S]*?)&lt;\/citation&gt;/g, '<blockquote class="citation"><q>$2</q><footer>- par $1</footer></blockquote>');
      field = field.replace(/&lt;citation lien=\"(.*?)\"&gt;([\s\S]*?)&lt;\/citation&gt;/g, '<blockquote class="citation" cite="$1"><q>$2</q><footer>- $1</footer></blockquote>');
      field = field.replace(/&lt;citation nom=\"(.*?)\" lien=\"(.*?)\"&gt;([\s\S]*?)&lt;\/citation&gt;/g, '<blockquote class="citation" cite="$2"><q>$3</q><footer>- $2 par $1</footer></blockquote>');
      field = field.replace(/&lt;citation&gt;([\s\S]*?)&lt;\/citation&gt;/g, '<blockquote class="citation"><q>$1</q></blockquote>');
      field = field.replace(/&lt;taille valeur=\"(.*?)\"&gt;([\s\S]*?)&lt;\/taille&gt;/g, '<span class="$1">$2</span>');
      
      for (var i=0, c=smiliesName.length; i<c; i++) {
         field = field.replace(new RegExp(" " + smiliesName[i] + " ", "g"), "&nbsp;<img src=\"" + smiliesPath + smiliesUrl[i] + "\" alt=\"" + smiliesUrl[i] + "\" />&nbsp;");
      }
      
      previewDiv.innerHTML = field;
   }
}


// Insérer une balise autour de la sélection (ou juste le curseur, s'il n'y a pas de sélection). 
function insertTag(startTag, endTag, textareaId, tagType) {
   var field  = document.getElementById(textareaId); // On récupère la zone de texte
   var scroll = field.scrollTop;                     // On met en mémoire la position du scroll
   field.focus(); // On remet le focus sur la zone de texte, suivant les navigateurs, on perd le focus en appelant la fonction.  

   /* === Partie 1 : on récupère la sélection === */
   var startSelection   = field.value.substring(0, field.selectionStart);
   var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
   var endSelection     = field.value.substring(field.selectionEnd);
   
   /* === Partie 2 : on analyse le tagType === */
   if (tagType) {
      switch (tagType) {
         case "lien":
            // Si c'est un lien
            endTag = "</lien>";
            if (currentSelection) { // Il y a une sélection
               if (currentSelection.indexOf("http://") == 0 || currentSelection.indexOf("https://") == 0 || currentSelection.indexOf("ftp://") == 0 || currentSelection.indexOf("www.") == 0) {
                  // La sélection semble être un lien. On demande alors le libellé
                  var label = prompt("Quel est le libellé du lien ?") || "";
                  startTag = "<lien url=\"" + currentSelection + "\">";
                  currentSelection = label;
               } else {
                  // La sélection n'est pas un lien, donc c'est le libelle. On demande alors l'URL
                  var URL = prompt("Quelle est l'url ?");
                  startTag = "<lien url=\"" + URL + "\">";
               }
            } else { // Pas de sélection, donc on demande l'URL et le libelle
               var URL = prompt("Quelle est l'url ?") || "";
               var label = prompt("Quel est le libellé du lien ?") || "";
               startTag = "<lien url=\"" + URL + "\">";
               currentSelection = label;                     
            }
         break;
         case "citation":
            // Si c'est une citation
            endTag = "</citation>";
            if (currentSelection) { // Il y a une sélection
               if (currentSelection.length > 30) { // La longueur de la sélection est plus grande que 30. C'est certainement la citation, le pseudo fait rarement 20 caractères
                  var auteur = prompt("Quel est l'auteur de la citation ?") || "";
                  startTag = "<citation nom=\"" + auteur + "\">";
                } else { // On a l'Auteur, on demande la citation
                  var citation = prompt("Quelle est la citation ?") || "";
                  startTag = "<citation nom=\"" + currentSelection + "\">";
                  currentSelection = citation;
                }
            } else { // Pas de selection, donc on demande l'Auteur et la Citation
               var auteur = prompt("Quel est l'auteur de la citation ?") || "";
               var citation = prompt("Quelle est la citation ?") || "";
               startTag = "<citation nom=\"" + auteur + "\">";
               currentSelection = citation;    
            }
         break;
      }
   }

   /* === Partie 3 : on insère le tout === */
   field.value = startSelection + startTag + currentSelection + endTag + endSelection;
   field.focus();
   field.setSelectionRange(startSelection.length + startTag.length, startSelection.length + startTag.length + currentSelection.length);
   field.scrollTop = scroll; // et on redéfinit le scroll.
}

/* Insertion d'une toolbar + events associés aux textarea */
function addToolsBar() {

   /* === Partie 1 : création des éléments html === */
   /* ToolsBar + event onClick */
   var toolsBar = document.createElement('div');
      toolsBar.id = 'editionBar';
      toolsBar.className = 'editionBar';

   /* PreviewCheck */
   var newCheckP = document.createElement('input');
      newCheckP.id = 'previsualisation';
      newCheckP.name = 'previsualisation';
      newCheckP.type = 'checkbox';
      newCheckP.value = 'previsualisation';

   /* GRAS */
   var newButtonG = document.createElement('input');
      newButtonG.id = 'buttonG';
      newButtonG.className = 'buttonG';
      newButtonG.type = 'button';
      newButtonG.value = 'G';
      newButtonG.addEventListener('click', function() {
         insertTag('<gras>','</gras>','post_content');
      }, false);

   /* ITALIQUE */
   var newButtonI = document.createElement('input');
      newButtonI.id = 'buttonI';
      newButtonI.className = 'buttonI';
      newButtonI.type = 'button';
      newButtonI.value = 'I';
      newButtonI.addEventListener('click', function() {
         insertTag('<italique>','</italique>','post_content');
      }, false);

   /* SOULIGNE */
   var newButtonS = document.createElement('input');
      newButtonS.id = 'buttonS';
      newButtonS.className = 'buttonS';
      newButtonS.type = 'button';
      newButtonS.value = 'S';
      newButtonS.addEventListener('click', function() {
         insertTag('<souligne>','</souligne>','post_content');
      }, false);

   /* LIEN */
   var newButtonL = document.createElement('input');
      newButtonL.id = 'buttonL';
      newButtonL.className = 'buttonL';
      newButtonL.type = 'button';
      newButtonL.value = 'L';
      newButtonL.addEventListener('click', function() {
         insertTag('<lien>','</lien>','post_content', 'lien');
      }, false);

   /* CITATION */
   var newButtonC = document.createElement('input');
      newButtonC.id = 'buttonC';
      newButtonC.className = 'buttonC';
      newButtonC.type = 'button';
      newButtonC.value = 'C';
      newButtonC.addEventListener('click', function() {
         insertTag('<citation nom=>','</citation>','post_content', 'citation');
      }, false);

   /* IMAGE */
   var newButtonF = document.createElement('input');
      newButtonF.id = 'buttonF';
      newButtonF.className = 'buttonF';
      newButtonF.type = 'button';
      newButtonF.value = 'Image';
      newButtonF.addEventListener('click', function() {
         insertTag('<image>','</image>','post_content');
      }, false);

   /* TAILLE */
   var newSelectT = document.createElement('select');
      newSelectT.id = 'selectT';
      newSelectT.className = 'selectT';
      newSelectT.addEventListener('change', function() {
         insertTag('<taille valeur="' + this.options[this.selectedIndex].value + '">', '</taille>', 'post_content');
      }, false);
      /* Taille none */
      var newOptionN = document.createElement('option');
         newOptionN.id = 'optionN';
         newOptionN.className = 'selected';
         newOptionN.value = 'none';
         newOptionN.selected = 'selected';
         var newOptionNText = document.createTextNode("None");
      /* Taille Très très petit */
      var newOptionTTP = document.createElement('option');
         newOptionTTP.id = 'optionTTP';
         newOptionTTP.value = 'ttpetit';
         var newOptionTTPText = document.createTextNode("Trés trés petit");
      /* Taille Très  petit */
      var newOptionTP = document.createElement('option');
         newOptionTP.id = 'optionTP';
         newOptionTP.value = 'tpetit';
         var newOptionTPText = document.createTextNode("Trés petit");
      /* Taille Petit */
      var newOptionP = document.createElement('option');
         newOptionP.id = 'optionP';
         newOptionP.value = 'petit';
         var newOptionPText = document.createTextNode("Petit");
      /* Taille Gros */
      var newOptionG = document.createElement('option');
         newOptionG.id = 'optionG';
         newOptionG.value = 'gros';
         var newOptionGText = document.createTextNode("Gros");
      /* Taille Très Gros */
      var newOptionTG = document.createElement('option');
         newOptionTG.id = 'optionTG';
         newOptionTG.value = 'tgros';
         var newOptionTGText = document.createTextNode("Trés gros");
      /* Taille Très très Gros */
      var newOptionTTG = document.createElement('option');
         newOptionTTG.id = 'optionTTG';
         newOptionTTG.value = 'ttgros';
         var newOptionTTGText = document.createTextNode("Trés trés gros");

   /* Div de preview*/
   var newDivPreview = document.createElement('div');
   newDivPreview.id ='previewDiv';


   /* === Partie 2 : assemblage des éléments html === */
   toolsBar.appendChild(newCheckP);
   toolsBar.appendChild(newButtonG);
   toolsBar.appendChild(newButtonI);
   toolsBar.appendChild(newButtonS);
   toolsBar.appendChild(newButtonL);
   toolsBar.appendChild(newButtonC);
   toolsBar.appendChild(newButtonF);
   toolsBar.appendChild(newSelectT);
      newSelectT.appendChild(newOptionN).appendChild(newOptionNText);
      newSelectT.appendChild(newOptionTTP).appendChild(newOptionTTPText);
      newSelectT.appendChild(newOptionTP).appendChild(newOptionTPText);
      newSelectT.appendChild(newOptionP).appendChild(newOptionPText);
      newSelectT.appendChild(newOptionG).appendChild(newOptionGText);
      newSelectT.appendChild(newOptionTG).appendChild(newOptionTGText);
      newSelectT.appendChild(newOptionTTG).appendChild(newOptionTTGText);

   /* === Partie 3 : récupére le textarea,son parent et le btn de validation, puis ajouter leurs events === */
   var textarea = document.querySelector('.textarea');
   textarea.addEventListener('keyup', function() {
        preview(textarea, newDivPreview);
    }, false);
   textarea.addEventListener('select', function() {
        preview(textarea, newDivPreview);
    }, false);

   // Récupère une référence du nœud parent
   var parentDiv = textarea.parentNode;

   /* === Partie 4 : rattache la toolsBar au body juste avant, et le preview après, un textarea  === */
   parentDiv.insertBefore(toolsBar, textarea);
   parentDiv.insertBefore(newDivPreview, textarea.nextSibling);
}

/* Initialisation */
function init() {
   if(document.querySelector('.textarea')){
      addToolsBar();
   }
}

/*Test limitant l'exécution du code aux navigateurs modernes supportant le DOM.*/
if (document.getElementById && document.createTextNode) {
  window.onload = init;
}