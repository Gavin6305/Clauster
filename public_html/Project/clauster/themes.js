// themes.js
export const themes = {
    default: {
        name: 'Original',
        backgroundColor: '#FFFFFF',
        menuTextColor: '#000000',
        buttonBorderColor: '#000000',
        game: {
            gameTextColor: '#000000',
            bulletColor: '#000000',
            zoneColor: '#FF8800',
            enemyColor: '#FF9191',
            charColor: '#5E9CFF',
            aimColor: '#FF0000',
        },
        endGameTextColor: '#000000',
    },
    dark: {
        name: 'Simple Black',
        backgroundColor: '#000000',
        menuTextColor: '#E5E1D0',
        buttonBorderColor: '#E5E1D0',
        game: {
            gameTextColor: '#E5E1D0',
            bulletColor: '#E5E1D0',
            zoneColor: '#E5E1D0',
            enemyColor: '#E5E1D0',
            charColor: '#E5E1D0',
            aimColor: '#E5E1D0',
        },
        endGameTextColor: '#E5E1D0',
    },
    pastel: {
        name: 'Pastel Heaven',
        backgroundColor: '#F9E1E0',
        menuTextColor: '#BC85A3',
        buttonBorderColor: '#BC85A3',
        game: {
            gameTextColor: '#BC85A3',
            bulletColor: '#87CEEB',
            zoneColor: '#BC85A3',
            enemyColor: '#FEADB9',
            charColor: '#9799BA',
            aimColor: '#9799BA',
        },
        endGameTextColor: '#BC85A3',
    },
};

export const themesList = [themes.default, themes.dark, themes.pastel];