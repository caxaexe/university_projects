/**
 * Represents the current activity fetched from a remote API.
 * @type {string|null}
 */
let currentActivity = null;

/**
 * Fetches a random activity from the Bored API and updates the current activity.
 * @async
 * @function
 * @returns {Promise<string|null>} A promise that resolves with the fetched activity string, or null if an error occurs.
 */
async function getRandomActivity() {
  try {
    const response = await fetch('http://www.boredapi.com/api/activity/');
    const data = await response.json();
    currentActivity = data.activity;
    updateActivity();
    return currentActivity;
  } catch (error) {
    console.error("К сожалению, произошла ошибка");
    return null;
  } finally {
    setTimeout(getRandomActivity, 60000); 
  }
}

/**
 * Updates the DOM with the current activity.
 * @function
 * @returns {void}
 */
function updateActivity() {
  if (currentActivity) {
    const activityElement = document.getElementById('activity');
    activityElement.textContent = currentActivity;
  } else {
        console.log("Не удалось получить активность");
  }
}

export { getRandomActivity, updateActivity };