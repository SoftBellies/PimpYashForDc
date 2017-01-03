# Yash 3
C'est un fork avec des modifications mineures du plugin Yash de [Dotclear](https://fr.dotclear.org/ "dotclear") http://plugins.dotaddict.org/dc2/details/yash (By pep and contributors GNU/GPL v2)

Ce module permet de la coloration syntaxique lors de la citation de codes dans un billet Dotclear.

# Utilisation

 ///yash3 langage
 
 some->code->here;
 
 ///

# Code tiers
Minifier class was made  by Tedious https://github.com/tedious/JShrink BSD License.

# Modifications apportées:

Le plugin Yash insère plusieurs appels à des scripts javascripts, et ajoute des variables JS dans le footer de la page.
Yash3 concatène tout ça pour ne mettre qu'un seul script en référence.
Il y aura quand même des appels à des scripts js (syntaxhighlighter) en fonction du type de code que vous souhaitez coloriser.

De même, les deux fichiers css sont réduits et concaténés en un seul.

Les noms de fichiers (css et js) générés sont incrémentés à chaque modification, afin d'éviter les soucis de cache utilisateur.

# Installation
Installez ce plugin en copiant l'url suivante https://www.ventresmous.fr/public/yash/yash3.zip directement dans l'interface de Dotclear pour l'installer.

Pensez à désactiver le plugin yash si vous l'aviez installé.