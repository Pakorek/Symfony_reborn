/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

console.log('app.js worked');

document.querySelector("#watchlist").addEventListener('click', switchToWatchlist);

function switchToWatchlist(e) {
    e.preventDefault();
    const watchlistDOM = e.currentTarget;
    const link = watchlistDOM.href;

    fetch(link)
        .then((res) => res.json())
        .then(res  => {
            if (res.isInWatchlist) {
                console.log('remove to fave')
                watchlistDOM.innerHTML = "Remove to fav"
            } else {
                console.log('add to fave')
                watchlistDOM.innerHTML = "Add to fav"
            }
        })
}
