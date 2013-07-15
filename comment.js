{
  cid: 42,
  user_id: 45455,                                     // uid de l’auteur du comment
  username: "Jipé du Nord",	                          // front - maj si modif
  avatar: "files/pictures/picture-4102-611mjcu2.jpg", // front - maj si modif
  url_page_perso: "page/jipe-du-nord-45455",          // front - maj si modif
  abonne: 1,                                          // (0|1 ou rid ?), front - maj si modif
  journaliste: 0,                                     // (0|1), front - maj si modif
  ip: "127.0.0.1",                                    // BO légal - Jamais mis à jour
  comment_texte: "Moi aussi.",                        // Front - Pas de maj. 
  note: {	                                            // Gestion des notes associées aux avis
    note: 4,                                          // Score brut: x
    total: [4, 5],                                    // Score x sur un maximum de y
  },

  // Cache du node parent.
  nid: 97001,                                         // nid du node commenté
  remote_id: "20130703FILWWW00428",                 	// id externe du node commenté
  appid: "figaro",                                		// appid du node commenté (pour le crm)
  titre:  "Immunité de Le Pen: Cohn-Bendit pour",   	// Front - maj si modif
  url: "http://www.lefigaro.fr/flash-actu/2013/07/03/97001-20130703FILWWW00428-immunite-de-le-pen-cohn-bendit-pour.php"	// front - maj si modif
  rubrique: "politique",                           	  // CRM export
  user_ids_auteur: [],                                //
  gid: [],                                           	// gids du node si publié dans un cercle.

  // Meta d'administration du commentaire
  status: 0,                                          // (0|1), statut du node si publié ou non. Maj par wf
  workflow: 7,                                        // [1-8], statut exact du wf du node. Maj par wf
  timestamp: 1372857716,                           	  // Date de post du commentaire, ne change jamais.
  pid: 42,                                        	  // Pid drupal way. Voir comment on fait évoluer ou non cela (warning migration)
  thread: ",42,",                                     // Thread drupal way.

  // Journalisation
  logs: {
    timestamp: 1372857716,
    old_status: 0,
    new_status: 1,
    message: "RAS",
    uid: null,
    username: "Un modérateur",
  }
}
