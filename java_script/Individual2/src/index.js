import { getRandomActivity } from './activity.js';
import { updateActivity } from './activity.js';

/**
 * Executes an asynchronous function to fetch a random activity and update it.
 * @async
 * @function
 * @returns {Promise<void>} A promise that resolves with no value.
 */
(async () => {
    let activity = await getRandomActivity();
    updateActivity(activity);
  })();
  
getRandomActivity();