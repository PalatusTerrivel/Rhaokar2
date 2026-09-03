baseCity = {
    popSize:0,
    basicAttributes: {
        'Corruption': 0,
        'Crime': 0,
        'Economy': 0,
        'Law': 0,
        'Lore': 0,
        'Society': 0,
        'Danger': 0
    },
    marketPlace: {
        'Base Value': 0,
        'Purchase Limit': 0,
        'Spellcasting': 0,
        'Minor Itens': 0,
        'Medium Itens': 0,
        'Major Itens': 0
    },
    aligment: {
        'N': {
            'Lore': 2
        },
        'NG': {
            'Lore': 1,
            'Society': 1
        },
        'NE': {
            'Corruption': 1,
            'Lore': 1
        },
        'LN': {
            'Law': 1,
            'Lore': 1
        },
        'LG': {
            'Law': 1,
            'Society': 1
        },
        'LE': {
            'Corruption': 1,
            'Law': 1
        },
        'CN': {
            'Crime': 1,
            'Lore': 1
        },
        'CG': {
            'Crime': 1,
            'Society': 1
        },
        'CE': {
            'Corruption': 1,
            'Crime': 1
        }
    },
    settlementType: {
        'Thorpe': {
            maxQualities:1,
            basicAttributes: {
                'Corruption': -4,
                'Crime': -4,
                'Economy': -4,
                'Law': -4,
                'Lore': -4,
                'Society': -4,
                'Danger': -10
            },
            marketPlace:{
                'Base Limit': 50,
                'Purchase Limit': 500,
                'Minor Itens': '1d4',
                'Medium Itens': 0,
                'Major Itens': 0,
                'Spellcasting': 1
            }
        },
        'Hamlet': {
            maxQualities:1,
            basicAttributes: {
                'Corruption': -2,
                'Crime': -2,
                'Economy': -2,
                'Law': -2,
                'Lore': -2,
                'Society': -2,
                'Danger': -5
            },
            marketPlace:{
                'Base Limit': 200,
                'Purchase Limit': 1000,
                'Minor Itens': '1d6',
                'Medium Itens': 0,
                'Major Itens': 0,
                'Spellcasting': 2
            }
        },
        'Village': {
            maxQualities:2,
            basicAttributes: {
                'Corruption': -1,
                'Crime': -1,
                'Economy': -1,
                'Law': -1,
                'Lore': -1,
                'Society': -1,
                'Danger': 0
            },
            marketPlace:{
                'Base Limit': 500,
                'Purchase Limit': 2500,
                'Minor Itens': '2d4',
                'Medium Itens': '1d4',
                'Major Itens': 0,
                'Spellcasting': 3
            }
        },
        'Small Town': {
            maxQualities:2,
            basicAttributes: {
                'Corruption': 0,
                'Crime': 0,
                'Economy': 0,
                'Law': 0,
                'Lore': 0,
                'Society': 0,
                'Danger': 0
            },
            marketPlace:{
                'Base Limit': 1000,
                'Purchase Limit': 5000,
                'Minor Itens': '3d4',
                'Medium Itens': '1d6',
                'Major Itens': 0,
                'Spellcasting': 4
            }
        },
        'Large Town': {
            maxQualities:3,
            basicAttributes: {
                'Corruption': 0,
                'Crime': 0,
                'Economy': 0,
                'Law': 0,
                'Lore': 0,
                'Society': 0,
                'Danger': 5
            },
            marketPlace:{
                'Base Limit': 2000,
                'Purchase Limit': 10000,
                'Minor Itens': '3d4',
                'Medium Itens': '2d4',
                'Major Itens': '1d4',
                'Spellcasting': 5
            }
        },
        'Small City': {
            maxQualities:4,
            basicAttributes: {
                'Corruption': 1,
                'Crime': 1,
                'Economy': 1,
                'Law': 1,
                'Lore': 1,
                'Society': 1,
                'Danger': 5
            },
            marketPlace:{
                'Base Limit': 4000,
                'Purchase Limit': 25000,
                'Minor Itens': '4d4',
                'Medium Itens': '3d4',
                'Major Itens': '1d6',
                'Spellcasting': 6
            }
        },
        'Large City': {
            maxQualities:5,
            basicAttributes: {
                'Corruption': 2,
                'Crime': 2,
                'Economy': 2,
                'Law': 2,
                'Lore': 2,
                'Society': 2,
                'Danger': 10
            },
            marketPlace:{
                'Base Limit': 8000,
                'Purchase Limit': 50000,
                'Minor Itens': '4d4',
                'Medium Itens': '3d4',
                'Major Itens': '2d4',
                'Spellcasting': 7
            }
        },
        'Metropolis': {
            maxQualities:6,
            basicAttributes: {
                'Corruption': 4,
                'Crime': 4,
                'Economy': 4,
                'Law': 4,
                'Lore': 4,
                'Society': 4,
                'Danger': 10
            },
            marketPlace:{
                'Base Limit': 16000,
                'Purchase Limit': 100000,
                'Minor Itens': 'All',
                'Medium Itens': '4d4',
                'Major Itens': '3d4',
                'Spellcasting': 8
            }
        }
    },
    settlementTypeBySize: function() {
        x = this.popSize
        /* Big O(n), worst O(n*7) */
        if (x < 21) {
            return this.settlementType[0];
        } else if (x < 61) {
            return this.settlementType[1];
        } else if (x < 201) {
            return this.settlementType[2];
        } else if (x < 2001) {
            return this.settlementType[3];
        } else if (x < 5001) {
            return this.settlementType[4];
        } else if (x < 10001) {
            return this.settlementType[5];
        } else if (x < 25,001) {
            return this.settlementType[6];
        } else {
            return this.settlementType[7];
        }
    },
    government: {
        'Autocracy': {
            basicAttributes: {},
            marketplace: {}
        },
        'Colonial': {
            basicAttributes: {},
            marketplace: {}
        },
        'Council': {
            basicAttributes: {},
            marketplace: {}
        },
        'Dynasty': {
            basicAttributes: {},
            marketplace: {}
        },
        'Magical': {
            basicAttributes: {},
            marketplace: {}
        },
        'Military': {
            basicAttributes: {},
            marketplace: {}
        },
        'Overlord': {
            basicAttributes: {},
            marketplace: {}
        },
        'Secret Syndicate': {
            basicAttributes: {},
            marketplace: {}
        },
        'Theocracy': {
            basicAttributes: {},
            marketplace: {}
        },
        'Plotocracy': {
            basicAttributes: {},
            marketplace: {}
        },
        'Utopian Experiment': {
            basicAttributes: {},
            marketplace: {}
        }
    },
    qualities: {
        'Academic': {
            basicAttributes: {'Lore': 1},
            marketplace: {'Spellcasting': 1}
        },
        'Holy Site': {
            basicAttributes: {'Corruption': -2},
            marketplace: {'Spellcasting': 2}
        },
        'Insular': {
            basicAttributes: {'Crime': -2, 'Law': 1},
            marketplace: {}
        },
        'Magically Attuned': {
            basicAttributes: {},
            marketplace: {'Base Value': 0.2, 'Purchase Limit': 0.2, 'Spellcasting': 2}
        },
        'Notorious': {
            basicAttributes: {'Crime': 1, 'Law': -1, 'Danger': 10},
            marketplace: {'Base Value': 0.3, 'Purchase Limit': 0.5}
        },
        'Pious': {
            basicAttributes: {},
            marketplace: {'Spellcasting': 1}
        },
        'Prosperous': {
            basicAttributes: {'Economy': 1},
            marketplace: {'Base Value': 0.3, 'Purchase Limit': 0.5}
        },
        'Racially Intollerant': {
            basicAttributes: {},
            marketplace: {}
        },
        'Rumormongering Citizens': {
            basicAttributes: {'Lore': 1, 'Society': -1},
            marketplace: {}
        },
        'Strategic Location': {
            basicAttributes: {'Economy': 1},
            marketplace: {'Base Value': 0.1}
        },
        'Supersticious': {
            basicAttributes: {'Crime': -4, 'Law': 2, 'Lore': 2},
            marketplace: {'Spellcasting':-2}
        },
        'Tourist Attraction': {
            basicAttributes: {'Economy': 1},
            marketplace: {'Base Value': 0.2}
        },
        'Boom Town': {
            basicAttributes: {'Crime': 1, 'Economy': 2},
            marketplace: {}
        },
        'Countercouture Movement': {
            basicAttributes: {'Corruption': -1, 'Law': -1, 'Lore': 1, 'Society': 1},
            marketplace: {}
        },
        'Financial': {
            basicAttributes: {'Corruption': 1, 'Economy': 2, 'Law': 1},
            marketplace: {'Base Value': 0.3, 'Purchase Value': 0.5}
        },
        'Government Capitol': {
            basicAttributes: {},
            marketplace: {'Base Value': 0.1, 'Purchase Value': 0.1}
        },
        'Hardened': {
            basicAttributes: {'Corruption': -2, 'Economy': 2, 'Lore': -2},
            marketplace: {}
        },
        'Industrial': {
            basicAttributes: {'Crime': 1, 'Law': 1, },
            marketplace: {'Base Value': 0.1, 'Purchase Value': 0.2}
        },
        'Massive Underground': {
            basicAttributes: {'Corruption': 2, 'Lore': -1, 'Society': 10},
            marketplace: {}
        },
        'Military Presence': {
            basicAttributes: {'Crime': 1, 'Economy': 1},
            marketplace: {'Base Value': 0.1}
        },
        'Monastic Order': {
            basicAttributes: {'Crime': 1, 'Economy': 1, 'Lore': -2},
            marketplace: {}
        },
        'On The shoulders of Giants': {
            basicAttributes: {'Crime': 1, 'Lore': 1},
            marketplace: {}
        },
        'Organized Crime': {
            basicAttributes: {'Corruption': 1,'Crime': 1, 'Economy': -2, 'Society': 10},
            marketplace: {}
        },
        'Party Town': {
            basicAttributes: {'Corruption': 1,'Crime': 1, 'Lore': 1},
            marketplace: {}
        },
        'Rabble Rousers': {
            basicAttributes: {'Crime': -1, 'Lore': -1},
            marketplace: {'Base Value': -0.1, 'Purchase Value': -0.1}
        },
        'Tribal': {
            basicAttributes: {'Economy': 2, 'Lore': -4},
            marketplace: {}
        },
        'Under Siege': {
            basicAttributes: {'Crime': 1, 'Lore': 1, 'Society': -10},
            marketplace: {}
        }
    },
    disadvantages: {
        'Bad Neighbors': {
            basicAttributes: {'Crime': 1, 'Economy': -1, 'Law': -1,'Society': -1, 'Danger': 10 },
            marketplace: {'Base Value': -0.1}
        },
        'Dead Magic': {
            basicAttributes: {},
            marketplace: {}
        },
        'Dying': {
            basicAttributes: {'Corruption': 4, 'Crime': 4, 'Economy': -6, 'Law': -4,'Society': -4 },
            marketplace: {'Base Value': -0.3, 'Purchase Value': -0.5}
        },
        'Hunted': {
            basicAttributes: {'Economy': -4, 'Law': -4,'Society': -4, 'Danger': 20 },
            marketplace: {'Base Value': -0.1}
        },
        'Haunted': {
            basicAttributes: {},
            marketplace: {}
        },
        'Plagued': {
            basicAttributes: {'Corruption': -2, 'Crime': -2, 'Economy': -2, 'Law': -2, 'Lore': 1,'Society': -2 },
            marketplace: {'Base Value': -0.2}
        },
        'Secluded': {
            basicAttributes: {'Economy': -2, 'Lore': -2,'Society': -4 },
            marketplace: {'Base Value': -0.5, 'Purchase Value': -0.5}
        },
        'Impoverished': {
            basicAttributes: {'Corruption': 1, 'Crime': 1 },
            marketplace: {'Base Value': -0.5, 'Purchase Value': -0.5}
        },
        'Spellblight': {
            basicAttributes: {'Lore': -4 },
            marketplace: {'Spellcasting': -3}
        },
        'Wild Magic': {
            basicAttributes: {},
            marketplace: {}
        }
    }

}