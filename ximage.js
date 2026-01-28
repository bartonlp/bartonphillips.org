/*
 * BLP 2022-09-28 - This now works for all of my sites:
 * https://bartonphillips.org:8080 on rpi
 * https://bartonphillips.org on HPenvy
 * https://bartonlp.org on this server at /ver/www/bartonlp.org
 * 
 * For the image slideshow at http://bartonphillips.org:8080/index.php on rpi2.
 *  This uses glob.proxy.php on www.bartonphillips.org:8080.
 *  glob.proxy.php returns a list of files in the 'path' of dobanner()
 *  The bannershow() function uses the 'bannerImages' array created by dobanner().
 *  'bannershow() displayes the images in "#show"
 */

import figlet from 'figlet';

let bannerImages = [], binx = 0;

/* Called from 'index.php' */

// dobanner()
// path is a pattern to glob on.
// obj: {size: size, recursive: yes|no, mode: seq|rand}

export
const dobanner = async (path, name, obj) => {
  console.log(`PATH=${path}, NAME=${name}, OBJ=`, obj);
  
  const { recursive, size, mode } = obj;

  try {
    // 1. Replace $.ajax with fetch
    // We use FormData to mimic a standard POST request
    const formData = new FormData();
    formData.append('path', path);
    formData.append('recursive', recursive);
    formData.append('size', size);
    formData.append('mode', mode);

    const response = await fetch('/index.php', {
      method: 'POST',
      body: formData
    });

    if (!response.ok) throw new Error('Network response was not ok');

    const data = await response.text();
    bannerImages = data.split("\n").filter(line => line.trim() !== "");

    // 2. Replace $("#show").html(...)
    const showDiv = document.querySelector("#show");
    if (showDiv) {
      showDiv.innerHTML = `<h3 class='center'>${name}</h3><img src="" alt="banner">`;
      bannershow(mode); 
    }

  } catch (err) {
    console.error("Error: ", err);
  }
};

export
const bannershow = (mode) => {
  const imgElement = document.querySelector("#show img");
  if (!imgElement) return;

  if (binx >= bannerImages.length) binx = 0;

  const currentImageSrc = bannerImages[binx++];

  // Just set the source of the visible image directly
  imgElement.src = currentImageSrc;

  // Set the timer for the next one
  setTimeout(() => bannershow(mode), 5000);
};

//window.dobanner = dobanner;
//window.bannershow = bannershow;
