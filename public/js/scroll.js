/**
 * Checks if we are at the bottom
 * @returns {boolean}
 * @author Gouache Nathan, https://www.30secondsofcode.org/js/s/bottom-visible/
 */
const bottomVisible = () =>
    document.documentElement.clientHeight + window.scrollY >=
    (document.documentElement.scrollHeight ||
        document.documentElement.clientHeight);

/**
 * Checks if an element is visible in the viewport
 * @param {*} element 
 * @author Thomas Cardon, https://stackoverflow.com/a/7557433
 * @returns {boolean}
 */
function isInViewport(element) {
  const rect = element.getBoundingClientRect();
  return (
    rect.top >= 0 &&
    rect.left >= 0 &&
    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
  );
}

/**
 * Scrolls to the next schedule
 * @param {Array} schedules
 * @param {number} index
 * @param {margin} margin
 * @author Thomas Cardon
*/
function scrollStep(schedules, i, numberOfRepetitions=0 , margin = 145) {
  console.log(numberOfRepetitions)
  if (schedules[i] == null) {
    // Si vous êtes déjà au dernier élément, faites défiler vers le haut de la page
    window.scrollTo({
      top: 0,
      left: window.scrollX,
      behavior: 'smooth'
    });

    // Réinitialiser la séquence de défilement en recommençant à partir du premier élément
    i = 0;
  } else {

    let dims = schedules[i].getBoundingClientRect();
    window.scrollTo({
      top: window.scrollY + dims.height + margin,
      left: window.scrollX,
      behavior: 'smooth'
    });

    // Démarrez la séquence de défilement vers le haut si vous êtes arrivé à la fin de la page
    if (bottomVisible()) {
      if (numberOfRepetitions<3){
        setTimeout(() => scrollStep(schedules, null, numberOfRepetitions+1), 5000);
        return;
      }
      else {
        window.location.reload(true)
        return;
      }
    }
  }

  setTimeout(() => scrollStep(schedules, i + 1, numberOfRepetitions), 5000);
}


/**
 * Starts the scrolling animation
 * @author Thomas Cardon
*/
function startScrollAnimation() {
  let schedules = Array.from(document.querySelectorAll('.table-responsive'));



  if (schedules.length > 0 && !isInViewport(schedules[schedules.length - 1]))
    setTimeout(() => scrollStep(schedules, 0, 0), 5000);
  else console.log('No schedules to scroll');
}

docReady(startScrollAnimation);