Activité 5
Activité5.4:
Une erreur survient entre l'ajout de la ligne dans la table de logs et l'update du statut de l'utilisateur.
Ainsi, le statut de l'utilisateur n'est pas mis à jour, mais l'action de mise à jour a néanmoins été enregistrée.
Il y a donc une incohérence des données.

Activité 5.5:

Lorsque l'on réintroduit l'énoncé erroné, une exception est levée au sein de la transaction.
Ainsi, le commit n'est pas réalisé et ni l'action de mise à jour ni le statut de l'utilisateur n'est modifié.
Donc cohérence des données.