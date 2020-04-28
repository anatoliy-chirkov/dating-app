class Profile
{
  constructor(page, url) {
    this.page = page;
    this.url = url;
  }

  parseData = async () => {
    await this.page.goto(this.url);

    return {
      url: this.url,
      name: await this.parseName(),
      age: await this.parseAge(),
      city: await this.parseCity(),
      wishes: await this.parseWishes(),
      ...await this.parseHeightAndWeight(),
      photos: await this.parseArrPhotos()
    };
  };

  parseName = async () => {
    return await this.page.$eval('#col_center div.prof_user_box div.flex div.w390.ml20 h1', el => el.textContent);
  };

  parseAge = async () => {
    return await this.page.$$('#col_center div.prof_user_box div.flex div.w390.ml20 > div.person-info-row')
      .then(
        els => els.pop().$eval('div.blue_g', el => el.textContent.substring(0, el.textContent.indexOf(',')).trim())
      );
  };

  parseCity = async () => {
    return await this.page.$eval('#col_center div.prof_user_box div.flex div.w390.ml20 > div.mt4.mbm4.blue_g',
      el => el.textContent.trim()
    );
  };

  parseWishes = async () => {
    return await this.page.$eval(
      '#col_center div.p19t13b15 > div.pt10 > ul.reset.mt7 > li', el => el.textContent.trim()
    );
  };

  parseHeightAndWeight = async () => {
    const appearance = await this.page.$$('#col_center div.p19t13b15 > div.mt29')
      .then(els => Array.isArray(els)
        ? els[0].$$eval('ul.list_info.show.li_mt10 > li', els => els[0].textContent.split(':')[1])
        : null
      );

    return {
      height: appearance.split(',')[0].trim(),
      weight: appearance.split(',')[1].trim()
    }
  };

  parseArrPhotos = async () => {
    const photos = [];

    await this.page.click('div.prof_photo.p_rel > a.prof_elite_frame.ef8');
    await this.page.waitFor('img#bigfoto');

    while (true) {
      await setTimeout(() => {}, 600);
      const src = await this.page.$eval('img#bigfoto', el => el.getAttribute('src'));

      if (photos.includes(src)) {
        continue;
      }

      photos.push(src);

      if (await this.page.$eval('div#btn_next', el => el.style.display === 'block')) {
        await this.page.click('div#btn_next');
        await this.page.waitForNavigation();
      } else {
        break;
      }
    }

    return photos;
  }
}

export default Profile;
