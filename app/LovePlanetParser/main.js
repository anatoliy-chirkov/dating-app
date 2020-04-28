'use strict';

const Puppeteer = require('puppeteer');
const ProfilePage = require('Pages/Profile');
//const Sequelize = require('sequelize');

const searchUrl = 'https://loveplanet.com/a-search/d-1/foto-1/pol-1/spol-2/bage-18/tage-45/geo-3159,4312,4400/purp-0/country-3159/region-4312/city-4400/';

const main = async () => {
  let browser = await Puppeteer.launch();
  let page = null;

  page = await browser.newPage();
  await page.goto(searchUrl);

  await page.setCookie({
    name:  'ext_session',
    value: 'YXBwPTEmb3M9MSZhcGk9MCZ2ZXI9ODAuMC4zOTg3LjE0OSZzaWdhPTU2MmUyNzE4OTNkZmIyMTBlNGRlYTAzZGFmOWRiY2JiJnVpZD00NDk0NjkxNCZzaWQ9MTc3NTM0NjE3OCZ0eXBlPTgmbW9kZT0wJnNpZz0xNzVhYzM0ZDk4OTE4ZjY5NjhmODcxNGVjNzhkMDhmOCZzaWcyPTlkNjRmZTYwNmRiYzkyZWNhZmNhMTAzYjI0MzAyNDI2',
    url:   'https://loveplanet.com/'
  });

  // TODO: page navigation | check that user exist | limit for unique links count in array
  const linksToProfiles = await page.$$eval(
    '#searchajax span.img_clr > a', users => users.map(user => user.getAttribute('href'))
  );

  for (const linkToProfile of linksToProfiles) {
    const profilePage = new ProfilePage(page, linkToProfile);
    const profileData = await profilePage.parseData();

    // TODO: save profile data
  }

  // TODO: set that task is closed
  await browser.close();
};

main();
