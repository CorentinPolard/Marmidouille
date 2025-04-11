import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');


// Formulaire de création de recette

document.addEventListener("turbo:load", () => {
    function addFormToCollection(e) {
        const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

        const item = document.createElement('li');
        const deleteItem = document.createElement('button');
        deleteItem.innerText = "Supprimer";
        deleteItem.addEventListener("click", removeItem);

        item.innerHTML = collectionHolder
            .dataset
            .prototype
            .replace(
                /__name__/g,
                collectionHolder.dataset.index
            );

        item.appendChild(deleteItem);
        collectionHolder.appendChild(item);

        collectionHolder.dataset.index++;
    };

    function removeItem(e) {
        e.currentTarget.parentElement.remove();
    }

    const deleteItem = document.querySelectorAll(".remove_item");
    deleteItem.forEach((btn) => btn.addEventListener('click', removeItem));

    document
        .querySelectorAll('.add_item_link')
        .forEach(btn => {
            btn.addEventListener("click", addFormToCollection)
        });
})
