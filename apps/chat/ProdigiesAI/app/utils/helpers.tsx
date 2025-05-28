// Function to generate random text with a maximum of 300 characters
export const getRandomText = () => {
    const possibleChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789 ';
    const textLength = Math.floor(Math.random() * 300) + 1;
    let randomText = '';
    for (let i = 0; i < textLength; i++) {
      randomText += possibleChars.charAt(Math.floor(Math.random() * possibleChars.length));
    }
    return randomText;
  };
  
  // Function to generate a random date and time
  export const getRandomDate = () => {
    const startDate = new Date(2023, 0, 1); // Start date: January 1, 2023
    const endDate = new Date(); // Current date
    const randomDate = new Date(startDate.getTime() + Math.random() * (endDate.getTime() - startDate.getTime()));
    const formattedDate = `${randomDate.getDate()} ${getMonthName(randomDate.getMonth())} ${randomDate.getFullYear()}`;
    const formattedTime = `${randomDate.getHours()}:${padZeroes(randomDate.getMinutes())} ${randomDate.getHours() >= 12 ? 'PM' : 'AM'}`;
    return `${formattedDate} - ${formattedTime}`;
  };
  
  // Helper function to get month name from month number
  const getMonthName = (month: number) => {
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return monthNames[month];
  };
  
  // Helper function to pad zeroes to single digit minutes
  const padZeroes = (num: number) => {
    return (num < 10 ? '0' : '') + num.toString();
  };
  