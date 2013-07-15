{
  cid: 42,
  nid: 97001,                                         // nid du node commenté
  comment_uid: 45455,
  node_uids: [6],                                     // Possibilité d'autres multiples, MAJ si modif
  gids: [4013, 5012],                                	// gids du node si publié dans un cercle.
  ip: "127.0.0.1",                                    // BO légal - Jamais mis à jour
  status: 0,                                          // (0|1), statut du node si publié ou non. Maj par wf
  workflow: 7,                                        // [1-8], statut exact du wf du node. Maj par wf
  timestamp: 1372857716,                           	  // Date de post du commentaire, ne change jamais.

  // Threading.
  // A évaluer techniquement: ce format correspond aux chemins matérialisés
  pid: 42,                                        	  // Pid drupal way. Voir comment on fait évoluer ou non cela (warning migration)
  thread: ",42,",                                     // Thread drupal way.
  // Alternative probable: table de clôture. Dans ce cas, le pid est conservé par sécurité, mais pas pour requêter:
  // on utilise une autre collection.

  // Contenu
  contenu: "Moi aussi.",                                   // Front - Pas de maj. 
  note: {	                                                 // Gestion des notes associées aux avis
    note: 4,                                               // Score brut: x
    total: [4, 5],                                         // Score x sur un maximum de y
  },

  embeds: {
    // Cache du node parent et des éventuels autres nodes (OG)
    node: {
      97001: {
        remote_id: "20130703FILWWW00428",                 	// id externe du node commenté
        appid: "figaro",                                		// appid du node commenté (pour le crm)
        titre:  "Immunité de Le Pen: Cohn-Bendit pour",   	// Front - maj si modif
        url: "http://www.lefigaro.fr/flash-actu/2013/07/03/97001-20130703FILWWW00428-immunite-de-le-pen-cohn-bendit-pour.php"	// front - maj si modif
        rubrique: "politique",                           	  // CRM export
      },
      4013: {
        remote_id: "4013",                                	// id externe d'un node référencé
        appid: "premium",                                		// appid du node référencé
        titre:  "Poètes",                                 	// Front - maj si modif
        url: "http://www.lefigaro.fr/cercle/poetes.php",  	// front - maj si modif
        rubrique: "litterature",                            // CRM export
      },
      5012: {
        remote_id: "5012",                                	// id externe du node référencé
        appid: "premium",                                		// appid du node commenté (pour le crm)
        titre:  "Disparus",                                 // Front - maj si modif
        url: "http://www.lefigaro.fr/cercle/disparus.php",	// front - maj si modif
        rubrique: "litterature",                            // CRM export
      }
    },
    // Cache des auteurs 
    user: {
      6: {
        username: "Le Figaro",	                            // front - maj si modif
        avatar: "files/pictures/picture-6-youplaboumz.jpg", // front - maj si modif
        url_page_perso: "page/lefigaro-6",                  // front - maj si modif
        abonne: 1,                                          // (0|1 ou rid ?), front - maj si modif
        journaliste: 1,                                     // (0|1), front - maj si modif
      },
      45455: {
        username: "Jipé du Nord",	                          // front - maj si modif
        avatar: "files/pictures/picture-4102-611mjcu2.jpg", // front - maj si modif
        url_page_perso: "page/jipe-du-nord-45455",          // front - maj si modif
        abonne: 1,                                          // (0|1 ou rid ?), front - maj si modif
        journaliste: 0,                                     // (0|1), front - maj si modif
      }
    }    
  },

  // Journalisation.
  logs: [
    {
    timestamp: 1372857211,
    old_status: 0,
    new_status: 1,
    message: "RAS",
    uid: null,
    username: "Soumission",
    },
    {
    timestamp: 1372857716,
    old_status: 1,
    new_status: 2,
    message: "RAS",
    uid: null,
    username: "Un modérateur",
    }
  ]
}
