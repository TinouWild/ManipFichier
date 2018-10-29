    <?php include('inc/head.php'); ?>
    <?php

    // SI TU LIS CE FICHIER POUR PROFITER DE MES COMMENTAIRES AUSSI MAGNIFIQUES QUE PROFESSIONNELS (#LOL), COMMENCE PAR LA PARTIE "CREATION DE L'ARBORESCENCE SUR LA PAGE"...
    // AUTREMENT C'EST CHAUD
    // BON COURAGE...



    //PARTIE EFFACEMENT (à lire si tu as déjà lu la partie arborescence)

        if (isset($_GET['type'])){  // on regarde si on a reçu des données via le GET (donc si le type est 'delete' ou 'edit', et si on a un nom de fichier dans la valeur 'var1')
            if ($_GET['type'] == 'delete') { // si le type est 'delete', c'est qu'on veut effacer le fichier contenu dans la valeur 'var1'
                if (isset($_GET['var1'])) {  // si on a bien quelque chose dans 'var1' (normalement oui, mais on sait jamais...)
                    if (is_dir($_GET['var1'])) {  // si le fichier en question est un dossier
                        rmdir($_GET['var1']);  // alors on efface le dossier (qui doit être vide, sinon ça va dire merde)
                    }else{  // sinon (donc si c'est autre chose qu'un dossier, peut importe ce que c'est)
                        unlink($_GET['var1']);  // on efface ce fichier
                    }
                }
            } // POUR LA SUITE, IL FAUT SE RENDRE TOUT EN BAS, TU Y TROUVERAS LA PARTIE QUI CONCERNE LA MODIFICATION DU FICHIER PAR L'UTILISATEUR...
        }




        // MODIFICATION DU FICHIER TXT OU HTML (à lire en tout dernier)
        if (isset($_POST['contenu'])){ // si on a reçu du contenu par le $_POST
            $file = fopen($_POST['file'],"w"); // on ouvre le fichier dont l'adresse était placé dans la variable 'file' (celle qu'on ne voulait pas afficher', le w sert à donner les droits d'écriture
            fwrite($file, $_POST['contenu']); // on écrit dans le fichier dont l'adresse est stockée dans $file et on y injecte le contenu
            fclose($file);  // on referme le fichier
        } // ET C'EST FINI ;)




    // CREATION DE L'ARBORESCENCE SUR LA PAGE  (c'est par là qu'on commence, et c'est là que c'est un peu tendu)

    // on veut créer une arborescence des fichiers contenus dans files, il faut donc faire des boucles pour tout afficher dans l'ordre

        //NIVEAU 1



        $dir = 'files/'; // on donne l'adresse du fichier de base, donc ici tout ce qui se trouve dans le dossier files
        $niveau1 = scandir($dir);  // on recherche tous les fichiers qui se trouvent dans $dir (donc tout ce qui se trouve dans le dossier files), on obtient un tableau $niveau1 avec tous les noms
        foreach ($niveau1 as $value){  // pour chaque élément qui se trouve dans notre tableau (et donc dans le dossier file), on stocke le nom du fichier dans $value, pour les traiter un par un
            if ($value == '.' or $value == '..'){  // si le nom du fichier est '.' ou '..', on ne fait rien (parce que scandir crée ses valeurs, je sais pas pourquoi
            }else{
                $type1 = mime_content_type('files/'.$value); // on cherche à connaitre le type de fichier (parce qu'il faut pouvoir éditer les txt et html)
                if ($type1 == 'text/plain' or $type1 == 'text/html') {  // si le fichier est un texte ou un html alors on fait la ligne qui suit
                    echo $value . ' -' . '<a href="index.php?type=delete&var1=files/' . $value . '">(delete)</a>' . '<a href="index.php?type=edit&var1=files/' . $value . '&var2='.$value.'">(edit)</a>'.'<br>';
                    // Comme c'est un fichier txt ou html, on affiche d'abord le nom de notre fichier ($value), et ensuite on crée le lien qui permettra de l'effacer, mais aussi celui pour le modifier
                    //
                    // le premier lien, pour effacer, contient des valeurs en GET qu'on place après un '?' (parce que c'est comme ça qu'on passe des valeurs dans l'adresse...)
                    //      la première valeur, 'type', nous servira juste à savoir quelle action on veut faire, ici on veut l'effacer donc le type est 'delete'
                    //      la deuxième valeur, 'var1', contient l'adresse du fichier en question, pour qu'on puisse donner au programme l'endroit où se trouve le fichier à effacer
                    //
                    // Le deuxième lien, pour éditer, ressemble beaucoup au premier à la différence que la valeur 'type' contient la mention 'edit', parce qu'on veut juste modifier le fichier
                    // après on a deux valeurs, var1 qui nous donne l'adresse du fichier, et var2 qui stocke juste le nom du fichier (optionnel)


                }else{ // le fichier n'est pas un texte ou un html
                    echo $value . ' -' . '<a href="index.php?type=delete&var1=files/' . $value . '">(delete)</a>' . '<br>'; // même chose mais sans la partie edit, puisque ce n'est pas un texte ou un html
                }



             //NIVEAU 2

                if (is_dir('files/'.$value)){ // si on se trouve dans un autre dossier, on doit changer l'adresse. Ce n'est plus juste files/, mais files/*le dossier suivant (donc notre $value actuel)*
                    $dir2 = 'files/'. $value; // la nouvelle adresse est donc files/*le nom du dossier (stocké dans $value)*
                    $niveau2 = scandir($dir2); // pareil, on cherche ce qui se trouve dans ce deuxième dossier et on met tout dans le tableau $niveau2
                    foreach ($niveau2 as $value2) { // on veut créer la liste de ce qui se trouve dans ce dossier
                        if ($value2 == '.' or $value2 == '..') { // bon là, ça marche tout comme au premier niveau, sauf que comme on est au deuxième niveau on met les noms de fichiers dans $value2
                        } else {
                            $type2 = mime_content_type('files/'.$value.'/'.$value2);  // l'adresse du fichier à se niveau est donc files/*dossier du premier niveau actuel ($value)* / *notre fichier actuel ($value2)
                            if ($type2 == 'text/plain' or $type2 == 'text/html') {
                                echo ' - '.$value2 . ' -' . '<a href="index.php?type=delete&var1=files/' . $value .'/'. $value2 . '">(delete)</a>' . '<a href="index.php?type=edit&var1=files/' . $value .'/'. $value2 . '&var2='.$value2.'">(edit)</a>'.'<br>';
                            }else{
                                echo ' - '.$value2 . ' -' . '<a href="index.php?type=delete&var1=files/' . $value.'/'.$value2 . '">(delete)</a>' . '<br>';
                            }
                            // ici ça marche tout pareil qu'au premier niveau, sauf que l'adresse qu'on stocke dans var1 est plus longue, puisque on est plus loin dans les fichiers...





                            //NIVEAU 3


                            if (is_dir('files/'.$value.'/'.$value2)){    // bon là, c'est la même chose, sauf un niveau plus loin dans les dossiers, on a donc un $value3
                                $dir3 = 'files/'. $value . '/' . $value2;
                                $niveau3 = scandir($dir3);
                                foreach ($niveau3 as $value3) {
                                    if ($value3 == '.' or $value3 == '..') {
                                    } else {
                                        $type3 = mime_content_type('files/'.$value.'/'.$value2.'/'.$value3);
                                        if ($type3 == 'text/plain' or $type3 == 'text/html') {
                                            echo ' ----- '.$value3 . ' -' . '<a href="index.php?type=delete&var1=files/' . $value .'/'. $value2 .'/'.$value3. '">(delete)</a>' . '<a href="index.php?type=edit&var1=files/' . $value .'/'. $value2 .'/'.$value3. '&var2='.$value3.'">(edit)</a>'.'<br>';
                                        }else{
                                            echo ' ----- '.$value3 . ' -' . '<a href="index.php?type=delete&var1=files/' . $value .'/'. $value2 .'/'.$value3. '">(delete)</a>' . '<br>';
                                        }

                                    }
                                }
                            }

                        }
                    }       // MAINTENANT TU PEUX REMONTER EN HAUT DE LA PAGE POUR LIRE LA SUITE, LA PARTIE EFFACEMENT
                }
            }
        }





    // PARTIE MODIFICATION DU CONTENU DU FICHIER TXT OU HTML PAR L'UTILISATEUR
    // on a vu en haut la partie qui sert à supprimer un fichier, maintenant on voit la partie pour modifier
    // elle se trouve en bas parce qu'on va créer un formulaire pour modifier le fichier et qu'on veut qu'il s'affiche sous la liste

    if (isset($_GET['type'])){ // comme pour la partie supprimer, on vérifie qu'on a reçu quelque chose dans le $_GET
        if ($_GET['type'] == 'edit') {  // si le type est bien 'edit', alors on cherche à modifier le fichier


            // formulaire de modification, je l'ai fait en php avec du html dedans, j'imagine que ça marche aussi dans l'autre sens...
            echo '<form method="POST" action="index.php">';  // notre formulaire va renvoyer les données à modifier avec la méthode POST, pour ne pas encombrer la méthode GET qu'on utilise déjà
            echo '<br><br>'.'Edit file : '.$_GET['var2'].'<br>'; // cette ligne affiche juste le nom du fichier à modifier, qu'on avait stocké dans la variable var2
            $contenu = file_get_contents($_GET['var1']);  // on récupère le contenu du fichier txt ou html grâce à l'adresse qu'on avait placé dans var1, et on met tout ça dans $contenu
            echo '<textarea name="contenu" style="width:70%; height: 250px">'.$contenu.'</textarea>'; // on crée un espace pour placer le texte $contenu, c'est là dedans que l'utilisateur apportera ses changements
            echo '<input name="file" type="hidden" value="'.$_GET['var1'].'"/>'; //plus chaud, on crée une valeur qu'on ne souhaite pas afficher, mais qui contient l'adresse du fichier à modifier, puisqu'on en aura besoin pour appliquer nos modifications plus tard
            echo '<input type="submit" value="Save changes"/>'; // le bouton qui va envoyer tout ça
            echo'</form>';
        }
    } // maintenant on remonte tout en haut pour trouver la dernière parie qui va modifier le fichier grâce à ce qu'on envoie dans le $_POST

    ?>
    <?php include('inc/foot.php'); ?>