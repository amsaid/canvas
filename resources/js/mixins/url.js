import md5 from 'md5';

export default {
  methods: {
    gravatar(email, size = 200) {
      let hash = md5(email.trim().toLowerCase());

      return `https://secure.gravatar.com/avatar/${hash}?d=retro&r=g&s=${size}`;
    },
    avatar(name, size = 200, background = 'random') {
      return `https://ui-avatars.com/api/?name=${name}&size=${size}&background=${background}`;
    },
  },
};
